@props([
  "weather" => null,
  "city" => "Padang"
])

<div
  class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden"
>
  @if($weather)
    <!-- Weather Icon with Background -->
    <div class="flex items-center justify-between mb-4">
      <div
        class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300"
      >
        <i class="bi bi-cloud-sun text-xl"></i>
      </div>
      <div class="text-right">
        <div class="text-2xl lg:text-3xl font-bold text-secondary">
          {{ $weather['Temperature']['Metric']['Value'] ?? '-' }}°C
        </div>
        <div class="text-sm text-gray-500">{{ $city }}</div>
      </div>
    </div>

    <!-- Weather Details -->
    <div>
      <h3 class="font-medium text-gray-900 mb-1">
        Cuaca Hari Ini
      </h3>
      <p class="text-sm text-gray-500 capitalize">
        {{ $weather['WeatherText'] ?? 'Tidak diketahui' }}
      </p>
    </div>

    <!-- Weather Icon from AccuWeather -->
    <div class="mt-4 flex items-center justify-between">
      <div class="flex items-center gap-2">
        <img 
          class="w-8 h-8"
          src="https://developer.accuweather.com/sites/default/files/{{ str_pad($weather['WeatherIcon'] ?? 1, 2, '0', STR_PAD_LEFT) }}-s.png"
          alt="Weather Icon"
          onerror="this.style.display='none'"
        >
        <span class="text-sm text-blue-600">{{ $city }}</span>
      </div>
      
      @if(isset($weather['Temperature']['Metric']['Value']) && $weather['Temperature']['Metric']['Value'] > 30)
        <span class="inline-flex items-center gap-1 text-xs font-semibold text-red-600 bg-red-100 px-2 py-1 rounded-full">
          <i class="bi bi-thermometer-high"></i>
          Panas
        </span>
      @elseif(isset($weather['Temperature']['Metric']['Value']) && $weather['Temperature']['Metric']['Value'] < 20)
        <span class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 bg-blue-100 px-2 py-1 rounded-full">
          <i class="bi bi-thermometer-low"></i>
          Sejuk
        </span>
      @else
        <span class="inline-flex items-center gap-1 text-xs font-semibold text-green-600 bg-green-100 px-2 py-1 rounded-full">
          <i class="bi bi-thermometer-half"></i>
          Nyaman
        </span>
      @endif
    </div>

    <!-- Bottom accent line -->
    <div
      class="absolute bottom-0 left-0 right-0 h-1 bg-blue-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 rounded-b-xl"
    ></div>

  @else
    <!-- Error State -->
    <div class="flex items-center justify-between mb-4">
      <div
        class="w-12 h-12 bg-gray-100 text-gray-400 rounded-xl flex items-center justify-center"
      >
        <i class="bi bi-cloud-slash text-xl"></i>
      </div>
      <div class="text-right">
        <div class="text-2xl lg:text-3xl font-bold text-gray-400">
          --°C
        </div>
        <div class="text-sm text-gray-500">{{ $city }}</div>
      </div>
    </div>

    <div>
      <h3 class="font-medium text-gray-900 mb-1">
        Cuaca Hari Ini
      </h3>
      <p class="text-sm text-gray-500">
        Data cuaca tidak tersedia
      </p>
    </div>

    <div class="mt-4 flex items-center justify-between">
      <div class="flex items-center gap-2 text-sm text-gray-400">
        <i class="bi bi-wifi-off"></i>
        <span>Tidak terhubung</span>
      </div>
    </div>
  @endif
</div>