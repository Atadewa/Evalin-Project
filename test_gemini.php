<?php

// Test Phi-3-mini API
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Http;

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiKey = $_ENV['PHI3_API_KEY'] ?? null;

if (!$apiKey) {
    echo "PHI3_API_KEY tidak ditemukan dalam file .env\n";
    exit(1);
}

$endpoint = 'http://127.0.0.1:11434/api/generate';

$prompt = "Anda adalah sistem penilaian otomatis untuk ujian essay. Berikan penilaian yang akurat berdasarkan kriteria berikut:

SOAL: Apa ibu kota Indonesia?

KUNCI JAWABAN: Jakarta

JAWABAN SISWA: Jakarta adalah ibu kota negara Indonesia

KRITERIA PENILAIAN:
- Skor maksimal: 25
- Berikan nilai berdasarkan ketepatan dan kelengkapan jawaban
- Jika jawaban benar sempurna: berikan 25
- Jika jawaban sebagian benar: berikan nilai proporsional
- Jika jawaban salah total: berikan 0

INSTRUKSI: Berikan HANYA angka penilaian (contoh: 25 atau 15.5 atau 0), tanpa teks tambahan apapun.

NILAI:";

echo "Testing Phi-3-mini API...\n";
echo "Endpoint: $endpoint\n";
echo "Prompt: " . substr($prompt, 0, 100) . "...\n\n";

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
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Code: $httpCode\n";

if ($error) {
    echo "cURL Error: $error\n";
    exit(1);
}

if ($httpCode !== 200) {
    echo "HTTP Error: $httpCode\n";
    echo "Response: $response\n";
    exit(1);
}

$data = json_decode($response, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo "JSON Decode Error: " . json_last_error_msg() . "\n";
    echo "Raw Response: $response\n";
    exit(1);
}

echo "Success!\n";
echo "Full Response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n";

// Extract the text response
$responseText = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
echo "\nExtracted Text: '$responseText'\n";

// Extract numeric value
if (preg_match('/(\d+(?:\.\d+)?)/', $responseText, $matches)) {
    $score = floatval($matches[1]);
    echo "Extracted Score: $score\n";
} else {
    echo "Could not extract numeric score from response\n";
}
