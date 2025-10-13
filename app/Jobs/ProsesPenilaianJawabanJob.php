<?php

namespace App\Jobs;

use App\Models\Siswa;
use App\Models\Soal;
use App\Models\Ujian;
use App\Models\UjianSiswa;
use App\Models\JawabanSiswa;
use App\Models\OpsiJawaban;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProsesPenilaianJawabanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ujianId;
    protected $userId;

    public function __construct($ujianId, $userId)
    {
        $this->ujianId = $ujianId;
        $this->userId = $userId;
    }

    public function handle()
    {
        try {
            $startTime = microtime(true);

            $ujian = Ujian::findOrFail($this->ujianId);
            $jawabanSiswa = JawabanSiswa::where('ujian_id', $this->ujianId)
                ->where('user_id', $this->userId)
                ->with('soal', 'opsiJawaban')
                ->get();

            $totalSkor = 0;
            $totalSoal = $jawabanSiswa->count();

            foreach ($jawabanSiswa as $jawaban) {
                $soal = $jawaban->soal;
                $skorDiperoleh = 0;
                $isBenar = false;

                if ($soal->tipe_soal === 'pilgan') {
                    // Multiple choice grading
                    if ($jawaban->opsi_id) {
                        $opsiJawaban = $jawaban->opsiJawaban;
                        if ($opsiJawaban && $opsiJawaban->is_correct) {
                            $skorDiperoleh = $soal->skor;
                            $isBenar = true;
                        }
                    }
                } else {
                    // Essay grading using LLaMA
                    $skorDiperoleh = $this->gradeEssayWithLLaMA($soal, $jawaban->jawaban_teks ?? $jawaban->jawaban_dipilih);
                    $isBenar = $skorDiperoleh > 0;
                }

                // Update jawaban siswa
                $jawaban->update([
                    'skor_diperoleh' => $skorDiperoleh,
                    'is_benar' => $isBenar,
                ]);

                $totalSkor += $skorDiperoleh;
            }

            // Calculate final score (percentage)
            $maxSkor = $jawabanSiswa->sum(function($jawaban) {
                return $jawaban->soal->skor;
            });

            $nilaiAkhir = $maxSkor > 0 ? ($totalSkor / $maxSkor) * 100 : 0;

            $endTime = microtime(true);
            $timeKoreksi = round($endTime - $startTime);

            // Update or create ujian siswa record
            $siswa = \App\Models\User::find($this->userId)->siswa;
            UjianSiswa::updateOrCreate(
                [
                    'ujian_id' => $this->ujianId,
                    'user_id' => $this->userId,
                    'siswa_id' => $siswa->id,
                ],
                [
                    'total_nilai' => round($nilaiAkhir, 2),
                    'nilai_1' => round($nilaiAkhir, 2), // LLaMA score
                    'nilai_2' => round($nilaiAkhir, 2), // Same for now
                    'status' => 'selesai',
                    'time_koreksi' => $timeKoreksi,
                    'status_penilaian' => false, // Waiting for teacher confirmation
                ]
            );

            Log::info("Grading completed for ujian {$this->ujianId}, user {$this->userId}. Final score: {$nilaiAkhir}");

        } catch (\Exception $e) {
            Log::error("Error in grading job: " . $e->getMessage());
            throw $e;
        }
    }

    private function gradeEssayWithLLaMA($soal, $jawabanSiswa)
    {
        if (empty($jawabanSiswa)) {
            return 0;
        }

        // Jawaban benar dari soal (jika ada)
        $jawabanBenar = $soal->jawaban_benar ?? '';

        $prompt = "Soal Esai:\n{$soal->pertanyaan}\n\n";

        // Tambahkan kunci jawaban jika tersedia
        if (!empty($jawabanBenar)) {
            $prompt .= "🔑 Kunci Jawaban (Jawaban yang Diharapkan):\n{$jawabanBenar}\n\n";
        }

        $prompt .= "Jawaban Siswa:\n{$jawabanSiswa}\n\n"
            . "Petunjuk Penilaian:\n"
            . "- Nilai diberikan dalam ANGKA DESIMAL, contoh: {$soal->skor}.0, 75.0, 0.0.\n"
            . "- Skor maksimal adalah {$soal->skor}.\n"
            . "- Jika jawaban siswa SEPENUHNYA BENAR secara makna dan isi (meskipun tidak identik secara kata per kata), berikan NILAI PENUH yaitu {$soal->skor}.\n"
            . "- Jika jawaban benar SEBAGIAN, berikan skor yang dikurangi secara proporsional.\n"
            . "- Jika jawaban SALAH TOTAL atau tidak sesuai, beri nilai 0.\n"
            . "- Abaikan gaya bahasa, typo, dan urutan kalimat, selama makna dan fakta tetap benar.\n"
            . "- Fokus hanya pada kebenaran FAKTUAL dan KELENGKAPAN isi jawaban.\n\n"
            . "Perintah:\n"
            . "Jawab HANYA dengan ANGKA DESIMAL tanpa penjelasan apa pun.\n\n"
            . "Skor:";

        // Daftar endpoint API yang akan dicoba
        $endpoints = [
            'http://localhost:11434/api/generate',
            'http://localhost:11435/api/generate',
            'http://127.0.0.1:11434/api/generate',
            'http://127.0.0.1:1234/v1/chat/completions'
        ];

        $lastError = null;

        // Coba setiap endpoint hingga berhasil
        foreach ($endpoints as $endpoint) {
            try {
                // Kurangi timeout menjadi 5 detik saja agar cepat fallback ke endpoint lain
                $response = Http::timeout(5)->post($endpoint, [
                    'model' => 'llama3',
                    'prompt' => $prompt,
                    'stream' => false,
                ]);

                if ($response->successful()) {
                    $output = $response->json('response') ?? '';
                    preg_match('/\d+(\.\d+)?/', $output, $matches);
                    $nilai = isset($matches[0]) ? floatval($matches[0]) : 0;

                    // Pastikan nilai tidak melebihi maksimum
                    $finalScore = min($nilai, $soal->skor);
                    Log::info("LLaMA grading successful using endpoint {$endpoint}. Score: {$finalScore}");
                    return $finalScore;
                }
            } catch (\Exception $e) {
                $lastError = $e;
                Log::warning("LLaMA grading failed with endpoint {$endpoint}: " . $e->getMessage());
                // Lanjutkan ke endpoint berikutnya
            }
        }

        // Semua endpoint gagal, gunakan metode penilaian sederhana
        Log::error("All LLaMA endpoints failed. Using fallback scoring method. Last error: " . ($lastError ? $lastError->getMessage() : "Unknown"));
        return $this->nilaiSederhana($soal, $jawabanSiswa, $jawabanBenar);
    }

    /**
     * Metode penilaian sederhana sebagai fallback ketika API LLaMA tidak tersedia
     */
    private function nilaiSederhana($soal, $jawabanSiswa, $jawabanBenar)
    {
        // Jika jawaban terlalu pendek, berikan nilai rendah
        $panjangJawaban = strlen(trim($jawabanSiswa));
        if ($panjangJawaban < 10) {
            return $soal->skor * 0.1; // 10% dari nilai maksimal
        }

        // Jika tidak ada kunci jawaban, nilai berdasarkan panjang
        if (empty($jawabanBenar)) {
            if ($panjangJawaban < 50) {
                return $soal->skor * 0.4; // 40% untuk jawaban pendek
            } elseif ($panjangJawaban < 100) {
                return $soal->skor * 0.6; // 60% untuk jawaban sedang
            } else {
                return $soal->skor * 0.8; // 80% untuk jawaban panjang dan detail
            }
        }

        // Jika ada kunci jawaban, bandingkan dengan jawaban siswa
        // 1. Ekstrak kata kunci dari jawaban benar (hilangkan stopwords)
        $kataKunciJawabanBenar = $this->ekstrakKataKunci($jawabanBenar);

        // 2. Ekstrak kata kunci dari jawaban siswa
        $kataKunciJawabanSiswa = $this->ekstrakKataKunci($jawabanSiswa);

        // 3. Hitung kesamaan berdasarkan kata kunci yang cocok
        $jumlahKataKunci = count($kataKunciJawabanBenar);
        if ($jumlahKataKunci == 0) {
            return $soal->skor * 0.5; // Default jika tidak ada kata kunci
        }

        $kataKunciCocok = 0;
        foreach ($kataKunciJawabanBenar as $kataKunci) {
            foreach ($kataKunciJawabanSiswa as $kataSiswa) {
                // Hitung kemiripan string (0-1)
                $similarity = $this->calculateStringSimilarity($kataKunci, $kataSiswa);
                if ($similarity > 0.7) { // Jika kemiripan > 70%
                    $kataKunciCocok++;
                    break; // Lanjut ke kata kunci berikutnya
                }
            }
        }

        // Hitung persentase kemiripan dan kembalikan nilai
        $persentaseKesamaan = $kataKunciCocok / $jumlahKataKunci;
        return $soal->skor * min(0.9, $persentaseKesamaan); // Max 90% dari nilai maksimal
    }

    /**
     * Ekstrak kata kunci dari teks (menghilangkan stopwords)
     */
    private function ekstrakKataKunci($teks)
    {
        // Stopwords dalam Bahasa Indonesia
        $stopwords = ['ada', 'adalah', 'adanya', 'adapun', 'agak', 'agaknya', 'agar', 'akan', 'akankah',
                     'akhir', 'akhiri', 'akhirnya', 'aku', 'akulah', 'amat', 'amatlah', 'anda', 'andalah',
                     'antar', 'antara', 'antaranya', 'apa', 'apaan', 'apabila', 'apakah', 'apalagi', 'apatah',
                     'artinya', 'asal', 'asalkan', 'atas', 'atau', 'ataukah', 'ataupun', 'awal', 'awalnya',
                     'bagai', 'bagaikan', 'bagaimana', 'bagaimanakah', 'bagaimanapun', 'bagi', 'bagian',
                     'bahkan', 'bahwa', 'bahwasanya', 'baik', 'bakal', 'bakalan', 'balik', 'banyak', 'bapak',
                     'baru', 'bawah', 'beberapa', 'begini', 'beginian', 'beginikah', 'beginilah', 'begitu',
                     'begitukah', 'begitulah', 'begitupun', 'bekerja', 'belakang', 'belakangan', 'belum',
                     'belumlah', 'benar', 'benarkah', 'benarlah', 'berada', 'berakhir', 'berakhirlah',
                     'berakhirnya', 'berapa', 'berapakah', 'berapalah', 'berapapun', 'berarti', 'berawal',
                     'berbagai', 'berdatangan', 'beri', 'berikan', 'berikut', 'berikutnya', 'berjumlah',
                     'berkali-kali', 'berkata', 'berkehendak', 'berkeinginan', 'berkenaan', 'berlainan',
                     'berlalu', 'berlangsung', 'berlebihan', 'bermacam', 'bermacam-macam', 'bermaksud',
                     'bermula', 'bersama', 'bersama-sama', 'bersiap', 'bersiap-siap', 'bertanya',
                     'bertanya-tanya', 'berturut', 'berturut-turut', 'bertutur', 'berujar', 'berupa',
                     'besar', 'betul', 'betulkah', 'biasa', 'biasanya', 'bila', 'bilakah', 'bisa', 'bisakah',
                     'boleh', 'bolehkah', 'bolehlah', 'buat', 'bukan', 'bukankah', 'bukanlah', 'bukannya',
                     'bulan', 'bung', 'cara', 'caranya', 'cukup', 'cukupkah', 'cukuplah', 'cuma', 'dahulu',
                     'dalam', 'dan', 'dapat', 'dari', 'daripada', 'datang', 'dekat', 'demi', 'demikian',
                     'demikianlah', 'dengan', 'depan', 'di', 'dia', 'diakhiri', 'diakhirinya', 'dialah',
                     'diantara', 'diantaranya', 'diberi', 'diberikan', 'diberikannya', 'dibuat', 'dibuatnya',
                     'didapat', 'didatangkan', 'digunakan', 'diibaratkan', 'diibaratkannya', 'diingat',
                     'diingatkan', 'diinginkan', 'dijawab', 'dijelaskan', 'dijelaskannya', 'dikarenakan',
                     'dikatakan', 'dikatakannya', 'dikerjakan', 'diketahui', 'diketahuinya', 'dikira',
                     'dilakukan', 'dilalui', 'dilihat', 'dimaksud', 'dimaksudkan', 'dimaksudkannya',
                     'dimaksudnya', 'diminta', 'dimintai', 'dimisalkan', 'dimulai', 'dimulailah', 'dimulainya',
                     'dimungkinkan', 'dini', 'dipastikan', 'diperbuat', 'diperbuatnya', 'dipergunakan',
                     'diperkirakan', 'diperlihatkan', 'diperlukan', 'diperlukannya', 'dipersoalkan',
                     'dipertanyakan', 'dipunyai', 'diri', 'dirinya', 'disampaikan', 'disebut', 'disebutkan',
                     'disebutkannya', 'disini', 'disinilah', 'ditambahkan', 'ditandaskan', 'ditanya',
                     'ditanyai', 'ditanyakan', 'ditegaskan', 'ditujukan', 'ditunjuk', 'ditunjuki', 'ditunjukkan',
                     'ditunjukkannya', 'ditunjuknya', 'dituturkan', 'dituturkannya', 'diucapkan', 'diucapkannya',
                     'diungkapkan', 'dong', 'dua', 'dulu', 'empat', 'enggak', 'enggaknya', 'entah', 'entahlah',
                     'guna', 'gunakan', 'hal', 'hampir', 'hanya', 'hanyalah', 'hari', 'harus', 'haruslah',
                     'harusnya', 'hendak', 'hendaklah', 'hendaknya', 'hingga', 'ia', 'ialah', 'ibarat',
                     'ibaratkan', 'ibaratnya', 'ibu', 'ikut', 'ingat', 'ingat-ingat', 'ingin', 'inginkah',
                     'inginkan', 'ini', 'inikah', 'inilah', 'itu', 'itukah', 'itulah', 'jadi', 'jadilah',
                     'jadinya', 'jangan', 'jangankan', 'janganlah', 'jauh', 'jawab', 'jawaban', 'jawabnya',
                     'jelas', 'jelaskan', 'jelaslah', 'jelasnya', 'jika', 'jikalau', 'juga', 'jumlah',
                     'jumlahnya', 'justru', 'kala', 'kalau', 'kalaulah', 'kalaupun', 'kalian', 'kami',
                     'kamilah', 'kamu', 'kamulah', 'kan', 'kapan', 'kapankah', 'kapanpun', 'karena',
                     'karenanya', 'kasus', 'kata', 'katakan', 'katakanlah', 'katanya', 'ke', 'keadaan',
                     'kebetulan', 'kecil', 'kedua', 'keduanya', 'keinginan', 'kelamaan', 'kelihatan',
                     'kelihatannya', 'kelima', 'keluar', 'kembali', 'kemudian', 'kemungkinan', 'kemungkinannya',
                     'kenapa', 'kepada', 'kepadanya', 'kesamaan', 'keseluruhan', 'keseluruhannya', 'keterlaluan',
                     'ketika', 'khususnya', 'kini', 'kinilah', 'kira', 'kira-kira', 'kiranya', 'kita',
                     'kitalah', 'kok', 'kurang', 'lagi', 'lagian', 'lah', 'lain', 'lainnya', 'lalu', 'lama',
                     'lamanya', 'lanjut', 'lanjutnya', 'lebih', 'lewat', 'lima', 'luar', 'macam', 'maka',
                     'makanya', 'makin', 'malah', 'malahan', 'mampu', 'mampukah', 'mana', 'manakala',
                     'manalagi', 'masa', 'masalah', 'masalahnya', 'masih', 'masihkah', 'masing',
                     'masing-masing', 'mau', 'maupun', 'melainkan', 'melakukan', 'melalui', 'melihat',
                     'melihatnya', 'memang', 'memastikan', 'memberi', 'memberikan', 'membuat', 'memerlukan',
                     'memihak', 'meminta', 'memintakan', 'memisalkan', 'memperbuat', 'mempergunakan',
                     'memperkirakan', 'memperlihatkan', 'mempersiapkan', 'mempersoalkan', 'mempertanyakan',
                     'mempunyai', 'memulai', 'memungkinkan', 'menaiki', 'menambahkan', 'menandaskan',
                     'menanti', 'menanti-nanti', 'menantikan', 'menanya', 'menanyai', 'menanyakan',
                     'mendapat', 'mendapatkan', 'mendatang', 'mendatangi', 'mendatangkan', 'menegaskan',
                     'mengakhiri', 'mengapa', 'mengatakan', 'mengatakannya', 'mengenai', 'mengerjakan',
                     'mengetahui', 'menggunakan', 'menghendaki', 'mengibaratkan', 'mengibaratkannya',
                     'mengingat', 'mengingatkan', 'menginginkan', 'mengira', 'mengucapkan', 'mengucapkannya',
                     'mengungkapkan', 'menjadi', 'menjawab', 'menjelaskan', 'menuju', 'menunjuk', 'menunjuki',
                     'menunjukkan', 'menunjuknya', 'menurut', 'menuturkan', 'menyampaikan', 'menyangkut',
                     'menyatakan', 'menyebutkan', 'menyeluruh', 'menyiapkan', 'merasa', 'mereka', 'merekalah',
                     'merupakan', 'meski', 'meskipun', 'meyakini', 'meyakinkan', 'minta', 'mirip', 'misal',
                     'misalkan', 'misalnya', 'mula', 'mulai', 'mulailah', 'mulanya', 'mungkin', 'mungkinkah',
                     'nah', 'naik', 'namun', 'nanti', 'nantinya', 'nyaris', 'nyatanya', 'oleh', 'olehnya',
                     'pada', 'padahal', 'padanya', 'pak', 'paling', 'panjang', 'pantas', 'para', 'pasti',
                     'pastilah', 'penting', 'pentingnya', 'per', 'percuma', 'perlu', 'perlukah', 'perlunya',
                     'pernah', 'persoalan', 'pertama', 'pertama-tama', 'pertanyaan', 'pertanyakan', 'pihak',
                     'pihaknya', 'pukul', 'pula', 'pun', 'punya', 'rasa', 'rasanya', 'rata', 'rupanya', 'saat',
                     'saatnya', 'saja', 'sajalah', 'saling', 'sama', 'sama-sama', 'sambil', 'sampai',
                     'sampai-sampai', 'sampaikan', 'sana', 'sangat', 'sangatlah', 'satu', 'saya', 'sayalah',
                     'se', 'sebab', 'sebabnya', 'sebagai', 'sebagaimana', 'sebagainya', 'sebagian', 'sebaik',
                     'sebaik-baiknya', 'sebaiknya', 'sebaliknya', 'sebanyak', 'sebegini', 'sebegitu', 'sebelum',
                     'sebelumnya', 'sebenarnya', 'seberapa', 'sebesar', 'sebetulnya', 'sebisanya', 'sebuah',
                     'sebut', 'sebutlah', 'sebutnya', 'secara', 'secukupnya', 'sedang', 'sedangkan', 'sedemikian',
                     'sedikit', 'sedikitnya', 'seenaknya', 'segala', 'segalanya', 'segera', 'seharusnya',
                     'sehingga', 'seingat', 'sejak', 'sejauh', 'sejenak', 'sejumlah', 'sekadar', 'sekadarnya',
                     'sekali', 'sekali-kali', 'sekalian', 'sekaligus', 'sekalipun', 'sekarang', 'sekarang',
                     'sekecil', 'seketika', 'sekiranya', 'sekitar', 'sekitarnya', 'sekurang-kurangnya',
                     'sekurangnya', 'sela', 'selain', 'selaku', 'selalu', 'selama', 'selama-lamanya',
                     'selamanya', 'selanjutnya', 'seluruh', 'seluruhnya', 'semacam', 'semakin', 'semampu',
                     'semampunya', 'semasa', 'semasih', 'semata', 'semata-mata', 'semaunya', 'sementara',
                     'semisal', 'semisalnya', 'sempat', 'semua', 'semuanya', 'semula', 'sendiri', 'sendirian',
                     'sendirinya', 'seolah', 'seolah-olah', 'seorang', 'sepanjang', 'sepantasnya',
                     'sepantasnyalah', 'seperlunya', 'seperti', 'sepertinya', 'sepihak', 'sering', 'seringnya',
                     'serta', 'serupa', 'sesaat', 'sesama', 'sesampai', 'sesegera', 'sesekali', 'seseorang',
                     'sesuatu', 'sesuatunya', 'sesudah', 'sesudahnya', 'setelah', 'setempat', 'setengah',
                     'seterusnya', 'setiap', 'setiba', 'setibanya', 'setidak-tidaknya', 'setidaknya',
                     'setinggi', 'seusai', 'sewaktu', 'siap', 'siapa', 'siapakah', 'siapapun', 'sini',
                     'sinilah', 'soal', 'soalnya', 'suatu', 'sudah', 'sudahkah', 'sudahlah', 'supaya', 'tadi',
                     'tadinya', 'tahu', 'tahun', 'tak', 'tambah', 'tambahnya', 'tampak', 'tampaknya',
                     'tandas', 'tandasnya', 'tanpa', 'tanya', 'tanyakan', 'tanyanya', 'tapi', 'tegas',
                     'tegasnya', 'telah', 'tempat', 'tengah', 'tentang', 'tentu', 'tentulah', 'tentunya',
                     'tepat', 'terakhir', 'terasa', 'terbanyak', 'terdahulu', 'terdapat', 'terdiri',
                     'terhadap', 'terhadapnya', 'teringat', 'teringat-ingat', 'terjadi', 'terjadilah',
                     'terjadinya', 'terkira', 'terlalu', 'terlebih', 'terlihat', 'termasuk', 'ternyata',
                     'tersampaikan', 'tersebut', 'tersebutlah', 'tertentu', 'tertuju', 'terus', 'terutama',
                     'tetap', 'tetapi', 'tiap', 'tiba', 'tiba-tiba', 'tidak', 'tidakkah', 'tidaklah',
                     'tiga', 'tinggi', 'toh', 'tunjuk', 'turut', 'tutur', 'tuturnya', 'ucap', 'ucapnya',
                     'ujar', 'ujarnya', 'umum', 'umumnya', 'ungkap', 'ungkapnya', 'untuk', 'usah',
                     'usai', 'waduh', 'wah', 'wahai', 'waktu', 'waktunya', 'walau', 'walaupun', 'wong',
                     'yaitu', 'yakin', 'yakni', 'yang', 'yg', 'dsb', 'dll', 'dst', 'pd', 'dr', 'utk'];

        // Ubah ke lowercase dan buang karakter khusus
        $teks = strtolower($teks);
        $teks = preg_replace('/[^\w\s]/', ' ', $teks);

        // Pisahkan kata-kata
        $kata = preg_split('/\s+/', $teks);

        // Filter stopwords
        $kataKunci = [];
        foreach ($kata as $k) {
            $k = trim($k);
            if (!empty($k) && strlen($k) > 2 && !in_array($k, $stopwords)) {
                $kataKunci[] = $k;
            }
        }

        // Hilangkan duplikat
        return array_unique($kataKunci);
    }

    /**
     * Menghitung kemiripan antar string (0-1)
     */
    private function calculateStringSimilarity($string1, $string2) {
        // Implementasi sederhana dari algoritma Levenshtein distance
        $distance = levenshtein($string1, $string2);
        $maxLength = max(strlen($string1), strlen($string2));
        if ($maxLength === 0) return 1.0; // Kedua string kosong, sama persis

        // Konversi jarak ke similarity score (0-1)
        return 1.0 - ($distance / $maxLength);
    }
}
