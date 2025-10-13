<?php

// Debug Laravel Exam Data
require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Setup database connection
$capsule = new Capsule;

$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'evalin',
    'username' => 'root', // Sesuaikan dengan username database Anda
    'password' => '',     // Sesuaikan dengan password database Anda
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

echo "=== DEBUGGING EXAM DATA ===\n\n";

try {
    // Get recent exam attempts
    $ujianSiswa = Capsule::table('ujian_siswa')
        ->orderBy('id', 'desc')
        ->limit(5)
        ->get();

    echo "Recent exam attempts:\n";
    foreach ($ujianSiswa as $us) {
        echo "ID: {$us->id}, Ujian: {$us->ujian_id}, Siswa: {$us->siswa_id}, Status: {$us->status}, Nilai: {$us->total_nilai}\n";
    }
    echo "\n";

    // Get most recent exam
    $latestExam = $ujianSiswa->first();
    if (!$latestExam) {
        echo "No exam attempts found\n";
        exit(1);
    }

    echo "Debugging latest exam (ID: {$latestExam->id}):\n";
    echo "Ujian ID: {$latestExam->ujian_id}\n";
    echo "Siswa ID: {$latestExam->siswa_id}\n";
    echo "Status: {$latestExam->status}\n";
    echo "Total Nilai: {$latestExam->total_nilai}\n\n";

    // Get answers for this exam
    $jawaban = Capsule::table('jawaban_siswa as js')
        ->join('soal as s', 'js.soal_id', '=', 's.id')
        ->where('js.siswa_id', $latestExam->siswa_id)
        ->where('s.ujian_id', $latestExam->ujian_id)
        ->select('js.*', 's.pertanyaan', 's.tipe_soal', 's.jawaban_benar')
        ->get();

    echo "Answers for this exam:\n";
    foreach ($jawaban as $j) {
        echo "Jawaban ID: {$j->id}\n";
        echo "Soal ID: {$j->soal_id}\n";
        echo "Tipe Soal: {$j->tipe_soal}\n";
        echo "Pertanyaan: " . substr($j->pertanyaan, 0, 50) . "...\n";
        echo "Kunci Jawaban: " . substr($j->jawaban_benar ?? 'null', 0, 50) . "...\n";
        echo "Jawaban Siswa (Teks): " . substr($j->jawaban_teks ?? 'null', 0, 50) . "...\n";
        echo "Jawaban Siswa (Dipilih): " . substr($j->jawaban_dipilih ?? 'null', 0, 50) . "...\n";
        echo "Skor Diperoleh: {$j->skor_diperoleh}\n";
        echo "Nilai LLM: {$j->nilai_llama3}\n";
        echo "Is Benar: " . ($j->is_benar ? 'true' : 'false') . "\n";
        echo str_repeat("-", 50) . "\n";
    }

    // Summary
    $totalSoal = $jawaban->count();
    $totalSkor = $jawaban->sum('skor_diperoleh');
    $essayCount = $jawaban->where('tipe_soal', 'essay')->count();
    $pilganCount = $jawaban->where('tipe_soal', 'pilgan')->count();

    echo "\nSUMMARY:\n";
    echo "Total soal: {$totalSoal}\n";
    echo "Essay count: {$essayCount}\n";
    echo "Pilgan count: {$pilganCount}\n";
    echo "Total skor dari jawaban: {$totalSkor}\n";
    echo "Total nilai ujian: {$latestExam->total_nilai}\n";
    echo "Match: " . ($totalSkor == $latestExam->total_nilai ? "YES" : "NO") . "\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
