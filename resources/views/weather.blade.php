@php
  use App\Helpers\WeatherTranslator;
@endphp

@extends("layouts.app")

@section("title", "Cuaca Padang")

@section("content")
  <div class="flex justify-center mt-10">
    <div class="bg-blue-100 p-6 rounded-xl shadow-md text-center max-w-sm">
      @isset($error)
        <p class="text-red-500 font-semibold">{{ $error }}</p>
      @else
        <h1 class="text-2xl font-bold text-gray-800 mb-1">{{ $city }}</h1>
        <p class="text-lg text-gray-700">
          {{ WeatherTranslator::translateWeatherText($weather["WeatherText"] ?? null) }}
        </p>

        <p class="text-4xl font-bold text-gray-900 my-2">
          {{ $weather["Temperature"]["Metric"]["Value"] ?? "-" }}°C
        </p>

        @if (isset($weather))
          <h1>Cuaca {{ $city }}</h1>
          <p>
            Kondisi:
            {{ WeatherTranslator::translateWeatherText($weather["WeatherText"] ?? null) }}
          </p>
          <p>
            Suhu:
            {{ $weather["Temperature"]["Metric"]["Value"] }}°{{ $weather["Temperature"]["Metric"]["Unit"] }}
          </p>
        @endif

        <img
          class="mx-auto mt-2"
          src="https://developer.accuweather.com/sites/default/files/{{ str_pad($weather["WeatherIcon"] ?? 1, 2, "0", STR_PAD_LEFT) }}-s.png"
          alt="Weather Icon"
        />
      @endisset
    </div>
  </div>
@endsection
