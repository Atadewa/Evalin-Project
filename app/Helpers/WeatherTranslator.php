<?php

namespace App\Helpers;

class WeatherTranslator
{
    public static function translateWeatherText($weatherText)
    {
        if (!$weatherText) {
            return 'Tidak diketahui';
        }

        $translations = [
            'Sunny' => 'Cerah',
            'Clear' => 'Cerah',
            'Mostly Sunny' => 'Sebagian Besar Cerah',
            'Partly Sunny' => 'Sebagian Cerah',
            'Hot' => 'Panas',

            'Cloudy' => 'Berawan',
            'Mostly Cloudy' => 'Sebagian Besar Berawan',
            'Partly Cloudy' => 'Sebagian Berawan',
            'Overcast' => 'Mendung',
            'Fog' => 'Berkabut',
            'Hazy' => 'Berkabut Tipis',

            'Rain' => 'Hujan',
            'Light Rain' => 'Hujan Ringan',
            'Heavy Rain' => 'Hujan Lebat',
            'Drizzle' => 'Gerimis',
            'Showers' => 'Hujan Lokal',
            'Thunderstorms' => 'Badai Petir',
            'T-Storms' => 'Badai Petir',
            'Intermittent Clouds' => 'Berawan Sebagian',

            'Windy' => 'Berangin',
            'Breezy' => 'Sepoi-sepoi',

            'Clear Night' => 'Malam Cerah',
            'Partly Cloudy Night' => 'Malam Sebagian Berawan',
            'Mostly Clear' => 'Sebagian Besar Cerah',

            'Cool' => 'Sejuk',
            'Warm' => 'Hangat',
            'Cold' => 'Dingin',
        ];

        if (isset($translations[$weatherText])) {
            return $translations[$weatherText];
        }

        $lowerWeatherText = strtolower($weatherText);
        foreach ($translations as $english => $indonesian) {
            if (strpos($lowerWeatherText, strtolower($english)) !== false) {
                return $indonesian;
            }
        }

        return $weatherText;
    }

    public static function getTemperatureDescription($temperature)
    {
        if ($temperature >= 35) {
            return 'Sangat Panas';
        } elseif ($temperature >= 30) {
            return 'Panas';
        } elseif ($temperature >= 25) {
            return 'Hangat';
        } elseif ($temperature >= 20) {
            return 'Nyaman';
        } elseif ($temperature >= 15) {
            return 'Sejuk';
        } else {
            return 'Dingin';
        }
    }

    public static function translateWindDirection($direction)
    {
        $directions = [
            'N' => 'Utara',
            'NE' => 'Timur Laut',
            'E' => 'Timur',
            'SE' => 'Tenggara',
            'S' => 'Selatan',
            'SW' => 'Barat Daya',
            'W' => 'Barat',
            'NW' => 'Barat Laut',
            'NNE' => 'Utara-Timur Laut',
            'ENE' => 'Timur-Timur Laut',
            'ESE' => 'Timur-Tenggara',
            'SSE' => 'Selatan-Tenggara',
            'SSW' => 'Selatan-Barat Daya',
            'WSW' => 'Barat-Barat Daya',
            'WNW' => 'Barat-Barat Laut',
            'NNW' => 'Utara-Barat Laut'
        ];

        return $directions[$direction] ?? $direction;
    }
}