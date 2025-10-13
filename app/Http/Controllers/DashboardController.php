<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Siswa;
use App\Models\Soal;
use App\Models\Ujian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Get weather data from AccuWeather API
     */
    private function getWeatherData()
    {
        $apiKey = env('ACCUWEATHER_API_KEY');
        $locationKey = '206120'; // Padang
        $city = 'Padang';

        // Use cache to prevent frequent API calls
        $weather = Cache::remember("weather_{$locationKey}", 900, function () use ($apiKey, $locationKey) {
            Log::info("⏳ Fetching new weather data for location {$locationKey} from API...");

            try {
                $response = Http::timeout(10)->get("https://dataservice.accuweather.com/currentconditions/v1/{$locationKey}", [
                    'apikey' => $apiKey,
                    'details' => true
                ]);

                if ($response->failed()) {
                    Log::error("❌ Failed to fetch weather data from AccuWeather API for {$locationKey}");
                    return null;
                }

                $data = $response->json()[0] ?? null;
                if ($data) {
                    Log::info("✅ Weather data successfully fetched and cached", ['data' => $data]);
                }
                return $data;
            } catch (\Exception $e) {
                Log::error("❌ Weather API Exception: " . $e->getMessage());
                return null;
            }
        });

        return [
            'weather' => $weather,
            'city' => $city
        ];
    }

   public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $totalUsers = User::count();
            $totalSiswa = Siswa::count();
            $siswaTerdaftar = Siswa::whereNotNull('user_id')->count();
            $totalGuru = Guru::whereNotNull('user_id')->count();
            $totalKelas = Kelas::count();
            $totalMapel = MataPelajaran::count();
            $totalBankSoal = Soal::count();
            $ujianBerlangsung = Ujian::where('jadwal', '<=', now())
                                    ->where('waktu_selesai', '>=', now())->count();
            $ujianAkanDatang = Ujian::where('jadwal', '>', now())->count();
            $totalJadwalUjian = Ujian::count();

            $komposisiPengguna = [
                'admin' => User::where('role', 'admin')->count(),
                'guru' => User::where('role', 'guru')->count(),
                'siswa' => User::where('role', 'siswa')->count(),
            ];

            // Get weather data for dashboard
            $weatherData = $this->getWeatherData();

            return view('dashboard.admin', compact(
                'totalUsers', 'totalSiswa', 'siswaTerdaftar', 'totalGuru',
                'totalKelas', 'totalMapel', 'totalBankSoal',
                'ujianBerlangsung', 'ujianAkanDatang', 'totalJadwalUjian',
                'komposisiPengguna', 'weatherData'
            ));
        }

        if ($user->role === 'guru') {
            $guru = Guru::where('user_id', $user->id)->first();
            $ujianSaya = Ujian::where('created_by', $guru->id)->count();
             $totalSiswa = Siswa::count();

            // Get weather data for dashboard
            $weatherData = $this->getWeatherData();

            return view('dashboard.guru', compact('guru', 'ujianSaya', 'totalSiswa', 'weatherData'));
        }

        if ($user->role === 'siswa') {
            $siswa = Siswa::where('user_id', $user->id)->first();
            $ujianMendatang = Ujian::where('jadwal', '>', now())->count();

            // Get weather data for dashboard
            $weatherData = $this->getWeatherData();

            return view('dashboard.siswa', compact('siswa', 'ujianMendatang', 'weatherData'));
        }

        abort(403);
    }

}