<?php

/**
 * Test Script untuk PHI-3 Mini Connection
 *
 * Usage: php test_phi3_connection.php
 *
 * Script ini akan test koneksi ke Ollama API dan parse response
 */

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Http;

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$endpoint = $_ENV['PHI3_ENDPOINT'] ?? 'http://10.138.0.4:11434/api/generate';

echo "=== PHI-3 Mini Connection Test ===\n";
echo "Endpoint: {$endpoint}\n";
echo "Timestamp: " . date('Y-m-d H:i:s') . "\n\n";

// Test 1: Simple prompt
echo "Test 1: Simple prompt\n";
echo "Prompt: 'Apa nama ibu kota Indonesia?'\n";
echo "Sending request...\n";

$startTime = microtime(true);

try {
    $response = \Illuminate\Support\Facades\Http::withHeaders([
        'Content-Type' => 'application/json',
        'Accept' => 'application/json'
    ])
    ->timeout(180)
    ->connectTimeout(30)
    ->post($endpoint, [
        'model' => 'phi3:mini',
        'prompt' => 'Apa nama ibu kota Indonesia?',
        'stream' => false
    ]);

    $endTime = microtime(true);
    $duration = round($endTime - $startTime, 2);

    echo "Response received in {$duration} seconds\n";
    echo "Status code: " . $response->status() . "\n";

    if ($response->successful()) {
        echo "✓ Connection successful!\n\n";

        $body = $response->body();
        echo "Raw response (first 500 chars):\n";
        echo substr($body, 0, 500) . "\n\n";

        // Parse response
        $decoded = json_decode($body, true);

        if (is_array($decoded) && isset($decoded['response'])) {
            echo "Parsed response: " . $decoded['response'] . "\n";
            echo "Format: Single JSON object\n";
        } else {
            // Try NDJSON
            $lines = explode("\n", $body);
            $tokens = [];

            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) continue;

                $lineData = json_decode($line, true);
                if (is_array($lineData) && isset($lineData['response'])) {
                    $tokens[] = $lineData['response'];
                }
            }

            if (!empty($tokens)) {
                $fullResponse = implode('', $tokens);
                echo "Parsed response: {$fullResponse}\n";
                echo "Format: NDJSON stream (total tokens: " . count($tokens) . ")\n";
            } else {
                echo "⚠ Could not parse response\n";
            }
        }

        echo "\n✓ Test 1 PASSED\n";
    } else {
        echo "✗ Request failed\n";
        echo "Response: " . $response->body() . "\n";
        echo "\n✗ Test 1 FAILED\n";
    }

} catch (\Throwable $e) {
    $endTime = microtime(true);
    $duration = round($endTime - $startTime, 2);

    echo "✗ Exception occurred after {$duration} seconds\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "\n✗ Test 1 FAILED\n";
}

echo "\n";
echo str_repeat("=", 50) . "\n\n";

// Test 2: Grading prompt
echo "Test 2: Grading prompt (similar to actual usage)\n";
echo "Prompt: Grade a student answer\n";
echo "Sending request...\n";

$gradingPrompt = "Anda adalah sistem penilaian otomatis untuk ujian essay. Berikan penilaian yang akurat berdasarkan kriteria berikut:

SOAL: Jelaskan pengertian fotosintesis?

KUNCI JAWABAN: Proses pembuatan makanan pada tumbuhan menggunakan sinar matahari, air, dan karbon dioksida

JAWABAN SISWA: Membuat makanan dari sinar matahari

KRITERIA PENILAIAN:
- Skor maksimal: 33.33
- Berikan nilai berdasarkan ketepatan dan kelengkapan jawaban
- Jika jawaban benar sempurna: berikan 33.33
- Jika jawaban sebagian benar: berikan nilai proporsional
- Jika jawaban salah total: berikan 0

INSTRUKSI: Berikan HANYA angka penilaian (contoh: 33.33 atau 15.5 atau 0), tanpa teks tambahan apapun.

NILAI:";

$startTime = microtime(true);

try {
    $response = \Illuminate\Support\Facades\Http::withHeaders([
        'Content-Type' => 'application/json',
        'Accept' => 'application/json'
    ])
    ->timeout(180)
    ->connectTimeout(30)
    ->post($endpoint, [
        'model' => 'phi3:mini',
        'prompt' => $gradingPrompt,
        'stream' => false
    ]);

    $endTime = microtime(true);
    $duration = round($endTime - $startTime, 2);

    echo "Response received in {$duration} seconds\n";
    echo "Status code: " . $response->status() . "\n";

    if ($response->successful()) {
        echo "✓ Connection successful!\n\n";

        $body = $response->body();
        echo "Raw response (first 500 chars):\n";
        echo substr($body, 0, 500) . "\n\n";

        // Parse response
        $responseText = '';
        $decoded = json_decode($body, true);

        if (is_array($decoded) && isset($decoded['response'])) {
            $responseText = $decoded['response'];
        } else {
            $lines = explode("\n", $body);
            $tokens = [];

            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) continue;

                $lineData = json_decode($line, true);
                if (is_array($lineData) && isset($lineData['response'])) {
                    $tokens[] = $lineData['response'];
                }
            }

            if (!empty($tokens)) {
                $responseText = implode('', $tokens);
            }
        }

        echo "Full parsed response: {$responseText}\n\n";

        // Extract numeric value
        if (preg_match('/(\d+(?:\.\d+)?)/', trim($responseText), $matches)) {
            $score = floatval($matches[1]);
            echo "✓ Extracted score: {$score}\n";

            if ($score >= 0 && $score <= 33.33) {
                echo "✓ Score is valid (within range 0-33.33)\n";
            } else {
                echo "⚠ Score out of range: {$score}\n";
            }
        } else {
            echo "⚠ Could not extract numeric score from response\n";
        }

        echo "\n✓ Test 2 PASSED\n";
    } else {
        echo "✗ Request failed\n";
        echo "Response: " . $response->body() . "\n";
        echo "\n✗ Test 2 FAILED\n";
    }

} catch (\Throwable $e) {
    $endTime = microtime(true);
    $duration = round($endTime - $startTime, 2);

    echo "✗ Exception occurred after {$duration} seconds\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "Error code: " . $e->getCode() . "\n";

    if (method_exists($e, 'getTrace')) {
        echo "\nStack trace (first 5 lines):\n";
        $trace = array_slice($e->getTrace(), 0, 5);
        foreach ($trace as $idx => $frame) {
            echo "  #{$idx} " . ($frame['file'] ?? 'unknown') . ":" . ($frame['line'] ?? '?') . "\n";
        }
    }

    echo "\n✗ Test 2 FAILED\n";
}

echo "\n";
echo str_repeat("=", 50) . "\n";
echo "Test completed at " . date('Y-m-d H:i:s') . "\n";
