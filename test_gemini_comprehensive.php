<?php

// Test comprehensive Phi-3-mini scoring
require_once 'vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiKey = $_ENV['PHI3_API_KEY'] ?? null;

if (!$apiKey) {
    echo "PHI3_API_KEY tidak ditemukan dalam file .env\n";
    exit(1);
}

function testPhi3Scoring($soal, $kunci, $jawaban, $skorMaksimal) {
    // For local Phi3 service use localhost endpoint (adjust if different)
    $endpoint = 'http://10.138.0.4:11434/api/generate';

    $prompt = "Anda adalah sistem penilaian otomatis untuk ujian essay. Berikan penilaian yang akurat berdasarkan kriteria berikut:

SOAL: {$soal}

KUNCI JAWABAN: {$kunci}

JAWABAN SISWA: {$jawaban}

KRITERIA PENILAIAN:
- Skor maksimal: {$skorMaksimal}
- Berikan nilai berdasarkan ketepatan dan kelengkapan jawaban
- Jika jawaban benar sempurna: berikan {$skorMaksimal}
- Jika jawaban sebagian benar: berikan nilai proporsional
- Jika jawaban salah total: berikan 0

INSTRUKSI: Berikan HANYA angka penilaian (contoh: {$skorMaksimal} atau 15.5 atau 0), tanpa teks tambahan apapun.

NILAI:";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $endpoint);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'contents' => [
            [
                'parts' => [
                    ['text' => $prompt]
                ]
            ]
        ]
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        return "HTTP Error: $httpCode";
    }

    // Try to parse NDJSON or structured responses
    $responseText = '';
    $decoded = json_decode($response, true);
    if (is_array($decoded) && isset($decoded['candidates'][0]['content']['parts'][0]['text'])) {
        $responseText = $decoded['candidates'][0]['content']['parts'][0]['text'];
    } else {
        // fallback to raw body or NDJSON lines
        $lines = preg_split('/\R/', $response);
        $parts = [];
        foreach ($lines as $ln) {
            $ln = trim($ln);
            if ($ln === '') continue;
            $j = json_decode($ln, true);
            if ($j && isset($j['response'])) $parts[] = $j['response'];
            elseif ($j && isset($j['text'])) $parts[] = $j['text'];
            else $parts[] = $ln;
        }
        $responseText = implode('', $parts);
    }

    // Extract numeric value
    if (preg_match('/(\d+(?:\.\d+)?)/', $responseText, $matches)) {
        return floatval($matches[1]);
    } else {
        return "Could not extract score from: '$responseText'";
    }
}

echo "=== TESTING PHI-3-MINI SCORING SYSTEM ===\n\n";

// Test cases
$testCases = [
    [
        'soal' => 'Apa ibu kota Indonesia?',
        'kunci' => 'Jakarta',
        'jawaban' => 'Jakarta adalah ibu kota negara Indonesia',
        'skorMaksimal' => 25,
        'expected' => 'Should be close to 25 (perfect answer)'
    ],
    [
        'soal' => 'Apa ibu kota Indonesia?',
        'kunci' => 'Jakarta',
        'jawaban' => 'Jakarta',
        'skorMaksimal' => 25,
        'expected' => 'Should be 25 (exact answer)'
    ],
    [
        'soal' => 'Apa ibu kota Indonesia?',
        'kunci' => 'Jakarta',
        'jawaban' => 'Bandung',
        'skorMaksimal' => 25,
        'expected' => 'Should be 0 (wrong answer)'
    ],
    [
        'soal' => 'Sebutkan 3 warna primer?',
        'kunci' => 'Merah, Biru, Kuning',
        'jawaban' => 'Merah dan Biru',
        'skorMaksimal' => 30,
        'expected' => 'Should be around 20 (partial answer)'
    ],
    [
        'soal' => 'Jelaskan pengertian fotosintesis?',
        'kunci' => 'Proses pembuatan makanan pada tumbuhan menggunakan sinar matahari, air, dan karbon dioksida',
        'jawaban' => 'Fotosintesis adalah proses tumbuhan membuat makanan dengan bantuan sinar matahari',
        'skorMaksimal' => 20,
        'expected' => 'Should be around 12-15 (partial but good understanding)'
    ]
];

foreach ($testCases as $i => $test) {
    echo "Test Case " . ($i + 1) . ":\n";
    echo "Soal: {$test['soal']}\n";
    echo "Kunci: {$test['kunci']}\n";
    echo "Jawaban: {$test['jawaban']}\n";
    echo "Skor Maksimal: {$test['skorMaksimal']}\n";
    echo "Expected: {$test['expected']}\n";

    $score = testPhi3Scoring($test['soal'], $test['kunci'], $test['jawaban'], $test['skorMaksimal']);

    echo "Result: $score\n";
    echo "Status: " . (is_numeric($score) ? "✓ SUCCESS" : "✗ FAILED") . "\n";
    echo str_repeat("-", 50) . "\n\n";

    // Add small delay to avoid rate limiting
    sleep(1);
}

echo "=== TESTING COMPLETE ===\n";
