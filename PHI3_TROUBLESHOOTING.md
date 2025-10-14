# PHI-3 Mini Integration Troubleshooting

## Konfigurasi yang Berhasil (Berdasarkan Testing Dosen)

### 1. Environment Variables
Di file `.env`:
```env
PHI3_ENDPOINT="http://10.138.0.4:11434/api/generate"
```

### 2. Request Configuration
```php
$payload = [
    'model' => 'phi3:mini',
    'prompt' => $prompt,
    'stream' => false, // Disable streaming untuk kemudahan parsing
];

$response = Http::withHeaders([
    'Content-Type' => 'application/json',
    'Accept' => 'application/json'
])
->timeout(180) // Response timeout 3 menit (model inference bisa lama)
->connectTimeout(30) // Connection timeout 30 detik
->retry(2, 100) // Retry 2 kali dengan delay 100ms
->post($endpoint, $payload);
```

### 3. Response Format
Ollama API bisa mengembalikan 2 format:

#### Format 1: Single JSON (jika stream=false berhasil)
```json
{
  "model": "phi3:mini",
  "created_at": "2025-10-14T00:40:40.425195080Z",
  "response": "33.33",
  "done": true,
  "context": [...],
  "total_duration": 945389569,
  "load_duration": 258071333,
  "prompt_eval_count": 20,
  "prompt_eval_duration": 241984983,
  "eval_count": 13,
  "eval_duration": 700648078
}
```

#### Format 2: NDJSON Stream (jika server tetap streaming)
```json
{"model":"phi3:mini","created_at":"2025-10-14T00:40:40.425195080Z","response":"Ind","done":false}
{"model":"phi3:mini","created_at":"2025-10-14T00:40:40.861333832Z","response":" ones","done":false}
{"model":"phi3:mini","created_at":"2025-10-14T00:41.292504288Z","response":"ia","done":false}
...
{"model":"phi3:mini","created_at":"2025-10-14T00:47.430212982Z","response":"","done":true,"context":[...],"total_duration":945389569,...}
```

### 4. Parsing Logic
```php
$body = $response->body();
$responseText = '';

// Coba decode sebagai single JSON object dulu
$decoded = json_decode($body, true);

if (is_array($decoded) && isset($decoded['response'])) {
    // Format single JSON
    $responseText = $decoded['response'];
} else {
    // Parse sebagai NDJSON (multiple JSON per line)
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
    
    // Gabungkan semua token
    $responseText = implode('', $tokens);
}
```

## Common Issues & Solutions

### Issue 1: cURL error 28 (Operation timed out)
**Penyebab:**
- Timeout terlalu pendek (60s tidak cukup untuk model inference)
- Model membutuhkan waktu lebih lama untuk generate response

**Solusi:**
```php
->timeout(180) // Naikkan jadi 3 menit
->connectTimeout(30) // Pisahkan connection timeout
```

### Issue 2: Empty response atau parse error
**Penyebab:**
- Response format tidak sesuai ekspektasi
- NDJSON tidak di-parse dengan benar

**Solusi:**
- Log raw response body untuk debugging
- Implement dual parsing (single JSON + NDJSON)
- Gunakan `explode("\n")` bukan `preg_split()` untuk line parsing

### Issue 3: Model tidak merespon dengan angka
**Penyebab:**
- Prompt kurang jelas
- Model mengembalikan teks penjelasan

**Solusi:**
```php
$prompt = "... INSTRUKSI: Berikan HANYA angka penilaian (contoh: {$skorPerEsai} atau 15.5 atau 0), tanpa teks tambahan apapun.\n\nNILAI:";
```

## Testing Commands

### Test dengan curl (seperti screenshot dosen):
```bash
curl http://10.138.0.4:11434/api/generate -d '{
  "model": "phi3:mini",
  "prompt": "Apa nama ibu kota Indonesia?"
}'
```

### Test dari PHP:
```bash
php artisan tinker
```
```php
$response = \Illuminate\Support\Facades\Http::timeout(180)
    ->post('http://10.138.0.4:11434/api/generate', [
        'model' => 'phi3:mini',
        'prompt' => 'Apa nama ibu kota Indonesia?',
        'stream' => false
    ]);
    
echo $response->body();
```

## Monitoring & Debugging

### Check logs:
```bash
tail -f storage/logs/laravel.log
```

### Important log points:
1. "Mencoba endpoint Phi-3-mini: ..." - Request dimulai
2. "Raw response body (first 500 chars): ..." - Response diterima
3. "Parsed dari single JSON object" atau "Parsed dari NDJSON stream" - Parsing berhasil
4. "Final parsed response untuk jawaban ID..." - Response final
5. "Nilai yang diekstrak dari API: ..." - Ekstraksi angka berhasil

## Performance Optimization

### Current Settings:
- Response timeout: 180s (3 menit)
- Connection timeout: 30s
- Retry: 2x dengan delay 100ms
- Stream: false (untuk kemudahan parsing)

### Jika masih lambat:
1. Check network latency ke server model
2. Monitor CPU/GPU usage di server Ollama
3. Consider menggunakan model yang lebih kecil atau quantized version
4. Implement queue/background job untuk grading (tidak block user)

## Server Requirements

### Ollama Server (10.138.0.4:11434):
- Ollama harus running
- Model phi3:mini harus sudah di-pull: `ollama pull phi3:mini`
- Port 11434 harus accessible dari web server
- Firewall rules: allow traffic dari IP web server

### Web Server:
- PHP curl extension enabled
- Timeout limits di php.ini cukup tinggi
- Memory limit cukup untuk handle large responses

## Fallback Strategy

Jika model endpoint down atau error:
```php
try {
    // Try AI grading
    $response = Http::timeout(180)->post(...);
    // ... parsing logic
} catch (\Throwable $e) {
    Log::error("AI grading failed: " . $e->getMessage());
    
    // Fallback: berikan nilai 0 atau mark untuk manual grading
    $jawaban->update([
        'skor_diperoleh' => 0,
        'is_benar' => false,
        'needs_manual_review' => true // tambah flag ini di migration
    ]);
}
```

## Credits
Based on successful implementation by Prof. [Name] demonstrated via curl testing.
