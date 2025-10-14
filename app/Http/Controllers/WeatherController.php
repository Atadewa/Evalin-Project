<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{
    public function padangWeather()
    {
        $apiKey = env('ACCUWEATHER_API_KEY');
        $locationKey = '206120'; // Padang
        $city = 'Padang';

        // Gunakan Cache agar tidak terus-menerus request ke API
        $weather = Cache::remember("weather_{$locationKey}", 900, function () use ($apiKey, $locationKey) {
            Log::info("⏳ Fetching new weather data for location {$locationKey} from API...");

            $response = Http::get("https://dataservice.accuweather.com/currentconditions/v1/{$locationKey}", [
                'apikey' => $apiKey,
                'details' => true
            ]);

            if ($response->failed()) {
                Log::error("❌ Gagal memuat data cuaca dari AccuWeather API untuk {$locationKey}");
                return null;
            }

            $data = $response->json()[0];
            Log::info("✅ Data cuaca berhasil diambil dan disimpan ke cache", ['data' => $data]);
            return $data;
        });

        // dd($weather);   

        // Jika cache kosong atau API gagal
        if (!$weather) {
            return view('weather', ['error' => 'Gagal memuat data cuaca.']);
        }

        // Kirim data ke view
        return view('weather', [
            'city' => $city,
            'weather' => $weather
        ]);
    }

    public function getWeatherApi()
    {
        $apiKey = env('ACCUWEATHER_API_KEY');
        $locationKey = '206120'; // Padang
        $city = 'Padang';

        // Gunakan Cache agar tidak terus-menerus request ke API
        $weather = Cache::remember("weather_{$locationKey}", 900, function () use ($apiKey, $locationKey) {
            Log::info("⏳ Fetching new weather data for API location {$locationKey}...");

            try {
                $response = Http::timeout(10)->get("https://dataservice.accuweather.com/currentconditions/v1/{$locationKey}", [
                    'apikey' => $apiKey,
                    'details' => true
                ]);

                if ($response->failed()) {
                    Log::error("❌ Gagal memuat data cuaca dari AccuWeather API untuk {$locationKey}");
                    return null;
                }

                $data = $response->json()[0] ?? null;
                Log::info("✅ Data cuaca API berhasil diambil dan disimpan ke cache", ['data' => $data]);
                return $data;
            } catch (\Exception $e) {
                Log::error("❌ Weather API Exception: " . $e->getMessage());
                return null;
            }
        });

        // Return JSON response
        return response()->json([
            'success' => $weather !== null,
            'city' => $city,
            'weather' => $weather,
            'message' => $weather ? 'Data cuaca berhasil diambil' : 'Gagal memuat data cuaca'
        ]);
    }
}
