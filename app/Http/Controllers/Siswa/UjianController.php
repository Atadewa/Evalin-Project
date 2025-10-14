<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Jobs\ProsesPenilaianJawabanJob;
use App\Models\JawabanSiswa;
use App\Models\OpsiJawaban;
use App\Models\Siswa;
use App\Models\Soal;
use App\Models\Ujian;
use App\Models\UjianSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class UjianController extends Controller
{
    public function index()
    {
        $siswa = Siswa::where('user_id', Auth::id())->firstOrFail();
        $now = Carbon::now('Asia/Jakarta');

        $ujians = Ujian::with([
            'mataPelajaran',
            'kelas',
            'ujianSiswa' => function ($query) use ($siswa) {
                $query->where('siswa_id', $siswa->id);
            }
        ])
            ->where('is_published', true) // Hanya tampilkan ujian yang sudah dipublikasi
            ->whereHas('kelas', function ($query) use ($siswa) {
                $query->where('kelas_id', $siswa->kelas_id);
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($ujian) use ($now, $siswa) {
                $ujianSiswa = $ujian->ujianSiswa->first();

                if ($ujianSiswa && $ujianSiswa->status === 'selesai') {
                    $ujian->nilai1 = $ujianSiswa->total_nilai ?? null;
                    $ujian->nilai2 = null; // Jika ada nilai kedua, sesuaikan
                    $ujian->status_penilaian = $ujianSiswa->status_penilaian ?? false;
                } else {
                    // Parse waktu dengan timezone yang konsisten
                    $jadwal = Carbon::parse($ujian->jadwal, 'Asia/Jakarta');
                    $examPeriodEndTime = Carbon::parse($ujian->waktu_selesai, 'Asia/Jakarta');

                    // Buat atau update status ujian siswa
                    if (!$ujianSiswa) {
                        $ujianSiswa = UjianSiswa::create([
                            'ujian_id' => $ujian->id,
                            'siswa_id' => $siswa->id,
                            'status' => 'incoming'
                        ]);
                        // Refresh relasi
                        $ujian->load('ujianSiswa');
                    }

                    $status = 'incoming';
                    if ($now->lt($jadwal)) {
                        $status = 'incoming';
                    } elseif ($now->between($jadwal, $examPeriodEndTime)) {
                        // Check if student has started and if their individual exam time is up
                        if ($ujianSiswa->waktu_mulai) {
                            $studentStartTime = Carbon::parse($ujianSiswa->waktu_mulai, 'Asia/Jakarta');
                            $examDurationMinutes = ($ujian->durasi_jam * 60) + $ujian->durasi_menit;
                            $studentEndTime = $studentStartTime->copy()->addMinutes($examDurationMinutes);
                            if ($now->gt($studentEndTime)) {
                                $status = 'ended';
                            } else {
                                $status = 'ongoing';
                            }
                        } else {
                            $status = 'ongoing';
                        }
                    } else {
                        $status = 'ended';
                    }

                    // Update status jika perlu
                    if ($ujianSiswa->status !== $status) {
                        $ujianSiswa->update(['status' => $status]);
                    }

                    $ujian->nilai1 = null;
                    $ujian->nilai2 = null;
                    $ujian->status_penilaian = false;
                }

                // Attach UjianSiswa to the Ujian object for easy access in the view
                $ujian->ujianSiswa = collect([$ujianSiswa]);

                return $ujian;
            });
        // dd($ujians);


        return view('siswa.ujian.index', compact('ujians'));
    }

    public function show($id)
    {
        $siswa = Siswa::where('user_id', Auth::id())->firstOrFail();

        $ujian = Ujian::with([
            'soals.opsiJawaban',
            'soals.jawabanSiswas' => function ($query) use ($siswa) {
                $query->where('siswa_id', $siswa->id);
            }
        ])
            ->where('is_published', true) // Pastikan ujian sudah dipublikasi
            ->findOrFail($id);

        $siswa = Siswa::where('user_id', Auth::id())->firstOrFail();

        // Check if student already completed the exam
        $ujianSiswa = UjianSiswa::where('ujian_id', $id)
            ->where('siswa_id', $siswa->id)
            ->first();

        if ($ujianSiswa && $ujianSiswa->status === 'selesai') {
            return redirect()->route('siswa.ujian.index')
                ->with('info', 'Anda sudah menyelesaikan ujian ini.');
        }

        $now = Carbon::now('Asia/Jakarta');
        $startTime = Carbon::parse($ujian->jadwal, 'Asia/Jakarta');
        $examPeriodEndTime = Carbon::parse($ujian->waktu_selesai, 'Asia/Jakarta');

        if ($now->lt($startTime)) {
            return redirect()->route('siswa.ujian.index')
                ->with('error', 'Ujian belum dimulai.');
        }

        if ($now->gt($examPeriodEndTime)) {
            return redirect()->route('siswa.ujian.index')
                ->with('error', 'Periode ujian sudah berakhir.');
        }

        // Create or update ujian siswa record with start time
        $ujianSiswa = UjianSiswa::updateOrCreate(
            [
                'ujian_id' => $id,
                'siswa_id' => $siswa->id,
            ],
            [
                'status' => 'ongoing',
                'waktu_mulai' => $ujianSiswa->waktu_mulai ?? $now, // Set start time only if not set
            ]
        );

        // Calculate end time based on exam duration from when student started
        $studentStartTime = Carbon::parse($ujianSiswa->waktu_mulai, 'Asia/Jakarta');
        $examDurationMinutes = ($ujian->durasi_jam * 60) + $ujian->durasi_menit;
        $endTime = $studentStartTime->copy()->addMinutes($examDurationMinutes);

        // Check if student's exam time has ended
        if ($now->gt($endTime)) {
            // Auto-finish the exam if time is up
            $ujianSiswa->update([
                'status' => 'selesai',
                'waktu_selesai' => $now
            ]);

            return redirect()->route('siswa.ujian.index')
                ->with('error', 'Waktu ujian Anda sudah habis.');
        }

        // Randomize question order
        $sessionKey = 'ujian_soal_order_' . $id;
        if (!session()->has($sessionKey)) {
            session([$sessionKey => $ujian->soals->pluck('id')->shuffle()->toArray()]);
        }

        $soalOrder = session($sessionKey);
        $sortedSoals = $ujian->soals->sortBy(function ($soal) use ($soalOrder) {
            return array_search($soal->id, $soalOrder);
        })->values();

        // Attach jawaban siswa untuk setiap soal
        foreach ($ujian->soals as $soal) {
            $soal->jawaban_siswa = $soal->jawabanSiswas->first();
        }

        return view('siswa.ujian.show', compact('ujian', 'siswa', 'endTime', 'sortedSoals', 'ujianSiswa'));
    }

    public function simpanJawaban(Request $request)
    {

        $request->validate([
            'soal_id' => 'required|exists:soal,id',
            'ujian_id' => 'required|exists:ujian,id',
            'jawaban_teks' => 'nullable|string',
        ]);

        $siswa = Siswa::where('user_id', Auth::id())->firstOrFail();
        $soal = Soal::findOrFail($request->soal_id);

        $jawabanData = [
            'ujian_id' => $request->ujian_id,
            'siswa_id' => $siswa->id,
            'soal_id' => $soal->id,
            'jawaban_teks' => $request->jawaban_teks,
            'waktu_dijawab' => now(),
        ];

        // Determine answer type based on soal type
        if ($soal->tipe_soal === 'pilgan') {
            // Multiple choice answer - find opsi_id if it exists
            $opsiJawaban = OpsiJawaban::where('soal_id', $soal->id)
                ->where('teks_opsi', $request->jawaban_teks)
                ->first();
            $jawabanData['opsi_id'] = $opsiJawaban ? $opsiJawaban->id : null;
            $jawabanData['jawaban_teks'] = null;
        } else {
            // Essay answer
            $jawabanData['opsi_id'] = null;
            $jawabanData['jawaban_teks'] = $request->jawaban_teks;
        }

        JawabanSiswa::updateOrCreate(
            [
                'siswa_id' => $siswa->id,
                'soal_id' => $request->soal_id,
            ],
            $jawabanData
        );

        return response()->json(['success' => true]);
    }

    /**
     * Sync draft answers submitted by PWA when device comes back online.
     * Expected payload: { ujian_id: int, drafts: [{ soal_id, jawaban_teks, opsi_id?, waktu_dijawab }] }
     */
    public function syncDraft(Request $request)
    {
        $request->validate([
            'ujian_id' => 'required|integer|exists:ujian,id',
            'drafts' => 'required|array',
            'drafts.*.soal_id' => 'required|integer|exists:soal,id',
            'drafts.*.jawaban_teks' => 'nullable|string',
            'drafts.*.opsi_id' => 'nullable|integer',
            'drafts.*.waktu_dijawab' => 'nullable|date'
        ]);

        $siswa = Siswa::where('user_id', Auth::id())->firstOrFail();
        $ujian = Ujian::findOrFail($request->ujian_id);

        // Basic anti-cheating: ensure ujian is published and student is allowed
        if (!$ujian->is_published) {
            return response()->json(['success' => false, 'message' => 'Ujian tidak tersedia untuk sinkronisasi.'], 403);
        }

        // Check exam period boundaries
        $now = Carbon::now('Asia/Jakarta');
        $startTime = Carbon::parse($ujian->jadwal, 'Asia/Jakarta');
        $examPeriodEndTime = Carbon::parse($ujian->waktu_selesai, 'Asia/Jakarta');

        if ($now->gt($examPeriodEndTime)) {
            return response()->json(['success' => false, 'message' => 'Periode ujian sudah berakhir, tidak dapat sinkronisasi.'], 403);
        }

        $saved = 0;
        DB::beginTransaction();
        try {
            foreach ($request->drafts as $d) {
                $soal = Soal::find($d['soal_id']);
                if (!$soal) continue;

                // If soal does not belong to this ujian, skip
                if ($soal->ujian_id != $ujian->id) continue;

                $jawabanData = [
                    'ujian_id' => $ujian->id,
                    'siswa_id' => $siswa->id,
                    'soal_id' => $soal->id,
                    'waktu_dijawab' => isset($d['waktu_dijawab']) ? Carbon::parse($d['waktu_dijawab']) : now(),
                ];

                if ($soal->tipe_soal === 'pilgan') {
                    // Prefer opsi_id if provided, otherwise try lookup by teks
                    if (!empty($d['opsi_id'])) {
                        $jawabanData['opsi_id'] = $d['opsi_id'];
                        $jawabanData['jawaban_teks'] = null;
                    } else {
                        $jawabanData['jawaban_teks'] = $d['jawaban_teks'] ?? null;
                        $opsiJawaban = OpsiJawaban::where('soal_id', $soal->id)
                            ->where('teks_opsi', $jawabanData['jawaban_teks'])
                            ->first();
                        $jawabanData['opsi_id'] = $opsiJawaban ? $opsiJawaban->id : null;
                        $jawabanData['jawaban_teks'] = null;
                    }
                } else {
                    $jawabanData['opsi_id'] = null;
                    $jawabanData['jawaban_teks'] = $d['jawaban_teks'] ?? null;
                }

                JawabanSiswa::updateOrCreate(
                    [
                        'siswa_id' => $siswa->id,
                        'soal_id' => $soal->id,
                    ],
                    $jawabanData
                );
                $saved++;
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error saat sinkronisasi draft PWA: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat sinkronisasi.'], 500);
        }

        return response()->json(['success' => true, 'saved' => $saved]);
    }


    public function selesaikanUjian(Request $request)
    {
        $request->validate([
            'ujian_id' => 'required|integer|exists:ujian,id'
        ]);

        $siswa = Siswa::where('user_id', Auth::id())->firstOrFail();
        $ujian = Ujian::findOrFail($request->ujian_id);
        $ujianId = $ujian->id;

        // Beri tahu client bahwa penilaian sedang dimulai
        session()->flash('processing_exam', true);

        // 1. Ambil semua jawaban siswa untuk ujian ini
        $semuaJawaban = JawabanSiswa::query()
            ->where('siswa_id', $siswa->id)
            ->whereHas('soal', function ($q) use ($ujianId) {
                $q->whereHas('ujian', function ($u) use ($ujianId) {
                    $u->whereKey($ujianId);
                });
            })
            ->with(['soal.ujian', 'opsiJawaban'])
            ->get();

        Log::info("Total jawaban ditemukan untuk ujian ID {$ujianId}, siswa ID {$siswa->id}: " . $semuaJawaban->count());

        // Debug setiap jawaban
        foreach ($semuaJawaban as $j) {
            Log::info("Jawaban ID {$j->id}: Soal ID {$j->soal_id}, Tipe: {$j->soal->tipe_soal}, Jawaban Teks: " . substr($j->jawaban_teks ?? 'null', 0, 50) . "...");
        }


        if ($semuaJawaban->isEmpty()) {
            // Jika tidak ada jawaban, langsung selesaikan ujian dengan nilai 0
            $ujianSiswa = UjianSiswa::where('ujian_id', $ujianId)
                ->where('siswa_id', $siswa->id)
                ->first();

            if ($ujianSiswa) {
                $ujianSiswa->update([
                    'status' => 'selesai',
                    'total_nilai' => 0,
                    'time_koreksi' => time() // Use Unix timestamp (integer)
                ]);
            } else {
                UjianSiswa::create([
                    'ujian_id' => $ujianId,
                    'siswa_id' => $siswa->id,
                    'status' => 'selesai',
                    'total_nilai' => 0,
                    'time_koreksi' => time() // Use Unix timestamp (integer)
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Ujian selesai tanpa jawaban.',
                'redirect_url' => route('siswa.ujian.hasil', ['ujian' => $ujianId])
            ]);
        }

        // 2. Siapkan variabel untuk penilaian
        $totalNilaiAi = 0;
        $totalSoal = $semuaJawaban->count();
        $skorPerEsai = round(100 / $totalSoal, 2);

        // 3. Looping setiap jawaban untuk dinilai
        foreach ($semuaJawaban as $jawaban) {
            // Pastikan relasi 'soal' ada
            if (!$jawaban->soal)
                continue;

            // Tentukan jenis soal (pilgan atau essay)
            $tipeSoal = $jawaban->soal->tipe_soal;

            // Untuk soal pilihan ganda
            if ($tipeSoal === 'pilgan') {
                // Cek apakah ada opsi jawaban yang dipilih
                if ($jawaban->opsiJawaban) {
                    // Periksa apakah opsi yang dipilih adalah jawaban yang benar
                    $isBenar = $jawaban->opsiJawaban->is_correct;

                    // Berikan nilai sesuai dengan kebenaran jawaban
                    $nilaiPerSoal = $isBenar ? $skorPerEsai : 0;

                    // Update jawaban siswa dengan skor
                    $jawaban->update([
                        'skor_diperoleh' => $nilaiPerSoal,
                        'is_benar' => $isBenar
                    ]);

                    // Tambahkan ke total nilai
                    $totalNilaiAi += $nilaiPerSoal;
                }
            }
            // Untuk soal essay
            else if ($tipeSoal === 'essay') {
                // Validasi bahwa ada jawaban teks
                if (empty($jawaban->jawaban_teks)) {
                    Log::info("Soal essay ID {$jawaban->soal->id} tidak memiliki jawaban teks, skip penilaian");
                    continue;
                }
                $jawabanBenar = $jawaban->soal->jawaban_benar ?? 'tidak ada kunci jawaban tersedia';

                // Log untuk debugging
                Log::info("=== DEBUGGING KOREKSI ESSAY ===");
                Log::info("Mengoreksi jawaban essay - Soal ID: {$jawaban->soal->id}, Siswa ID: {$siswa->id}");
                Log::info("Pertanyaan: " . substr($jawaban->soal->pertanyaan ?? 'kosong', 0, 100) . "...");
                Log::info("Kunci jawaban: " . ($jawabanBenar === 'tidak ada kunci jawaban tersedia' || empty($jawabanBenar) ? '[TIDAK ADA]' : substr($jawabanBenar, 0, 100) . "..."));
                Log::info("Jawaban siswa: " . substr($jawaban->jawaban_teks ?? 'kosong', 0, 100) . "...");
                Log::info("Skor per essay: {$skorPerEsai}");
                Log::info("Mode penilaian: " . ($jawabanBenar === 'tidak ada kunci jawaban tersedia' || empty($jawabanBenar) ? 'RELEVANSI' : 'KUNCI_JAWABAN'));

                // Modifikasi prompt berdasarkan ketersediaan kunci jawaban
                if ($jawabanBenar === 'tidak ada kunci jawaban tersedia' || empty($jawabanBenar)) {
                    $prompt = "Anda adalah sistem penilaian otomatis untuk ujian essay. Nilai jawaban berdasarkan relevansi dengan pertanyaan:\n\n"
                        . "SOAL: {$jawaban->soal->pertanyaan}\n\n"
                        . "JAWABAN SISWA: {$jawaban->jawaban_teks}\n\n"
                        . "KRITERIA PENILAIAN:\n"
                        . "- Skor maksimal: {$skorPerEsai}\n"
                        . "- Nilai berdasarkan relevansi jawaban dengan pertanyaan\n"
                        . "- Jika jawaban sangat relevan dan lengkap: berikan {$skorPerEsai}\n"
                        . "- Jika jawaban cukup relevan: berikan 70-80% dari {$skorPerEsai}\n"
                        . "- Jika jawaban kurang relevan: berikan 40-60% dari {$skorPerEsai}\n"
                        . "- Jika jawaban tidak relevan atau kosong: berikan 0\n\n"
                        . "INSTRUKSI: Berikan HANYA angka penilaian (contoh: {$skorPerEsai} atau 35.5 atau 0), tanpa teks tambahan.\n\n"
                        . "NILAI:";
                } else {
                    $prompt = "Anda adalah sistem penilaian otomatis untuk ujian essay. Berikan penilaian yang akurat berdasarkan kriteria berikut:\n\n"
                        . "SOAL: {$jawaban->soal->pertanyaan}\n\n"
                        . "KUNCI JAWABAN: {$jawabanBenar}\n\n"
                        . "JAWABAN SISWA: {$jawaban->jawaban_teks}\n\n"
                        . "KRITERIA PENILAIAN:\n"
                        . "- Skor maksimal: {$skorPerEsai}\n"
                        . "- Berikan nilai berdasarkan ketepatan dan kelengkapan jawaban\n"
                        . "- Jika jawaban benar sempurna: berikan {$skorPerEsai}\n"
                        . "- Jika jawaban sebagian benar: berikan nilai proporsional\n"
                        . "- Jika jawaban salah total: berikan 0\n\n"
                        . "INSTRUKSI: Berikan HANYA angka penilaian (contoh: {$skorPerEsai} atau 15.5 atau 0), tanpa teks tambahan apapun.\n\n"
                        . "NILAI:";
                }

                try {
                    // Use configurable Phi-3 endpoint. If deployed, set PHI3_ENDPOINT in .env to e.g. http://10.138.0.4:11434/api/generate
                    $endpoint = env('PHI3_ENDPOINT', 'http://10.138.0.4:11434/api/generate');

                    // Log activity untuk monitoring di server
                    Log::info("Phi-3-mini sedang mengoreksi jawaban siswa ID: {$siswa->id}, Soal ID: {$jawaban->soal->id}");

                    // Tambahkan delay kecil untuk memberikan kesan AI sedang berpikir
                    usleep(500000); // 0.5 detik delay

                    // 2. Kirim request dengan struktur body untuk Ollama API
                    Log::info("Mencoba endpoint Phi-3-mini: {$endpoint}");

                    $payload = [
                        'model' => 'phi3:mini',
                        'prompt' => $prompt,
                        'stream' => false, // Disable streaming untuk response yang lebih mudah di-parse
                    ];

                    // Tingkatkan timeout ke 180 detik (3 menit) karena model inference bisa lama
                    // Tambahkan retry mechanism dan connect timeout terpisah
                    $response = Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json'
                    ])
                    ->timeout(180) // Response timeout 3 menit
                    ->connectTimeout(30) // Connection timeout 30 detik
                    ->retry(2, 100) // Retry 2 kali dengan delay 100ms jika gagal
                    ->post($endpoint, $payload);

                    if ($response->successful()) {
                        Log::info("Berhasil terhubung ke endpoint Phi-3-mini: {$endpoint}");
                    }

                    $nilaiPerSoalAi = 0;
                    if ($response && $response->successful()) {
                        $body = $response->body();
                        $responseText = '';

                        Log::info("Raw response body (first 500 chars): " . substr($body, 0, 500));

                        // Parse response dari Ollama API
                        // Format 1: Single JSON object dengan key 'response' (jika stream=false berhasil)
                        // Format 2: NDJSON - multiple JSON objects per line (jika server tetap streaming)

                        // Coba decode sebagai single JSON object dulu
                        $decoded = json_decode($body, true);

                        if (is_array($decoded) && isset($decoded['response'])) {
                            // Format single JSON: {"model":"phi3:mini","created_at":"...","response":"33.33","done":true,...}
                            $responseText = $decoded['response'];
                            Log::info("Parsed dari single JSON object, response: {$responseText}");
                        } else {
                            // Jika bukan single JSON, parse sebagai NDJSON (multiple JSON per line)
                            // Contoh dari screenshot: setiap line adalah JSON object dengan field "response"
                            $lines = explode("\n", $body);
                            $tokens = [];

                            foreach ($lines as $line) {
                                $line = trim($line);
                                if (empty($line)) continue;

                                $lineData = json_decode($line, true);
                                if (is_array($lineData)) {
                                    // Ambil token dari field 'response'
                                    if (isset($lineData['response'])) {
                                        $tokens[] = $lineData['response'];
                                    }

                                    // Log jika sudah done
                                    if (isset($lineData['done']) && $lineData['done'] === true) {
                                        Log::info("Response streaming selesai (done=true)");
                                    }
                                }
                            }

                            // Gabungkan semua token menjadi response lengkap
                            $responseText = implode('', $tokens);
                            Log::info("Parsed dari NDJSON stream, total tokens: " . count($tokens) . ", response: {$responseText}");
                        }

                        Log::info("Final parsed response untuk jawaban ID {$jawaban->id}: " . substr($responseText, 0, 500));

                        // Bersihkan response text dari whitespace
                        $cleanedText = trim($responseText);

                        // Cari pattern angka dengan berbagai format
                        if (preg_match('/(\d+(?:\.\d+)?)/', $cleanedText, $matches)) {
                            $nilaiDariApi = floatval($matches[1]);
                            Log::info("Nilai yang diekstrak dari API: {$nilaiDariApi}");
                        } else {
                            Log::warning("Tidak dapat mengekstrak nilai dari response API. Contoh tebal: '" . substr($cleanedText,0,200) . "'");
                            // Sebagai fallback, jika ada jawaban teks yang tidak kosong, berikan nilai proporsional
                            if (!empty(trim($jawaban->jawaban_teks))) {
                                $nilaiDariApi = $skorPerEsai * 0.5; // 50% dari skor maksimal sebagai fallback
                                Log::info("Menggunakan fallback score 50%: {$nilaiDariApi}");
                            } else {
                                $nilaiDariApi = 0;
                            }
                        }

                        // Validasi nilai (harus >= 0 dan <= skor maksimal)
                        if ($nilaiDariApi >= 0 && $nilaiDariApi <= $skorPerEsai) {
                            $nilaiPerSoalAi = $nilaiDariApi; // Tidak perlu min() karena sudah dicek

                            // Tentukan apakah jawaban dianggap benar (jika nilainya >= 70% dari nilai maksimal)
                            $isBenar = $nilaiPerSoalAi >= ($skorPerEsai * 0.7);

                            // Update jawaban siswa dengan nilai dan status
                            $jawaban->update([
                                'nilai_llama3' => $nilaiPerSoalAi,
                                'skor_diperoleh' => $nilaiPerSoalAi,
                                'is_benar' => $isBenar
                            ]);

                            Log::info("Jawaban ID: {$jawaban->id} berhasil dinilai dengan skor: {$nilaiPerSoalAi} dari maksimal: {$skorPerEsai}");
                        } else if ($nilaiDariApi > $skorPerEsai) {
                            // Jika nilai melebihi maksimal, set ke maksimal
                            $nilaiPerSoalAi = $skorPerEsai;
                            $jawaban->update([
                                'nilai_llama3' => $nilaiPerSoalAi,
                                'skor_diperoleh' => $nilaiPerSoalAi,
                                'is_benar' => true
                            ]);
                            Log::info("Jawaban ID: {$jawaban->id} dinilai dengan skor maksimal: {$nilaiPerSoalAi} (nilai API: {$nilaiDariApi})");
                        } else {
                            // Nilai negatif - invalid
                            Log::warning("Nilai negatif dari model endpoint untuk jawaban ID: {$jawaban->id}. Nilai: {$nilaiDariApi}");
                            $nilaiPerSoalAi = 0;
                            $jawaban->update([
                                'nilai_llama3' => 0,
                                'skor_diperoleh' => 0,
                                'is_benar' => false
                            ]);
                        }
                    } else {
                        Log::error("Request ke model endpoint gagal untuk jawaban ID: {$jawaban->id}");
                        if ($response) {
                            Log::error("Response status: " . $response->status());
                            Log::error("Response body: " . $response->body());
                        }
                        // Update dengan nilai 0 jika gagal
                        $jawaban->update([
                            'nilai_llama3' => 0,
                            'skor_diperoleh' => 0,
                            'is_benar' => false
                        ]);
                    }

                    // Tambahkan ke total nilai
                    $totalNilaiAi += $nilaiPerSoalAi;
                } catch (\Throwable $e) {
                    Log::error("Exception saat menilai jawaban ID {$jawaban->id}: " . $e->getMessage());
                    // Catat detail error untuk debugging
                    Log::error("Error trace: " . $e->getTraceAsString());

                    // Jika satu soal gagal, kita lanjutkan ke soal berikutnya
                    // Nilai tetap 0 untuk soal ini
                    continue;
                }
            }
        }

        // 4. Setelah semua jawaban dinilai, simpan nilai total dan status
        $ujianSiswa = UjianSiswa::where('ujian_id', $ujianId)
            ->where('siswa_id', $siswa->id)
            ->first();

        if ($ujianSiswa) {
            $ujianSiswa->update([
                'status' => 'selesai',
                'total_nilai' => round($totalNilaiAi, 2), // Simpan total nilai yang sudah diakumulasi
                'waktu_selesai' => now(), // Catat waktu siswa selesai ujian
                'time_koreksi' => now() // Catat waktu selesai penilaian
            ]);
        } else {
            UjianSiswa::create([
                'ujian_id' => $ujianId,
                'siswa_id' => $siswa->id,
                'status' => 'selesai',
                'total_nilai' => round($totalNilaiAi, 2), // Simpan total nilai yang sudah diakumulasi
                'waktu_mulai' => now(), // Default start time if not set
                'waktu_selesai' => now(), // Catat waktu siswa selesai ujian
                'time_koreksi' => now() // Catat waktu selesai penilaian
            ]);
        }
        return response()->json([
            'success' => true,
            'message' => 'Ujian berhasil disimpan.',
            'processing_complete' => true,
            'redirect_url' => route('siswa.ujian.hasil', ['ujian' => $ujianId])
        ]);
    }

    public function hasil($id)
    {
        $ujian = Ujian::with('mataPelajaran')->findOrFail($id);
        $siswa = Siswa::where('user_id', Auth::id())->firstOrFail();

        $ujianSiswa = UjianSiswa::where('ujian_id', $id)
            ->where('siswa_id', $siswa->id)
            ->first();

        if (!$ujianSiswa || $ujianSiswa->status !== 'selesai') {
            return redirect()->route('siswa.ujian.index')
                ->with('error', 'Anda belum menyelesaikan ujian ini.');
        }

        $jawabanSiswa = JawabanSiswa::query()
            ->where('siswa_id', $siswa->id)
            ->whereHas('soal.ujian', fn($q) => $q->whereKey($id))
            ->with(['soal.ujian'])
            ->get();


        $totalSoal = $ujian->soals()->count();
        $soalTerjawab = $jawabanSiswa->count();

        return view('siswa.ujian.hasil', compact('ujian', 'ujianSiswa', 'jawabanSiswa', 'totalSoal', 'soalTerjawab'));
    }
}
