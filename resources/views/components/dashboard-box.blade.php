@props([
  "title",
  "value",
  "icon" => "bi-graph-up",
  "color" => "primary",
])

@php
  $colorClasses = match ($color) {
    "primary" => ["bg" => "bg-primary", "text" => "text-primary", "light" => "bg-blue-50"],
    "success" => ["bg" => "bg-green-500", "text" => "text-green-600", "light" => "bg-green-50"],
    "danger" => ["bg" => "bg-red-500", "text" => "text-red-600", "light" => "bg-red-50"],
    "warning" => ["bg" => "bg-yellow-500", "text" => "text-yellow-600", "light" => "bg-yellow-50"],
    "info" => ["bg" => "bg-blue-500", "text" => "text-blue-600", "light" => "bg-blue-50"],
    default => ["bg" => "bg-gray-500", "text" => "text-gray-600", "light" => "bg-gray-50"],
  };
@endphp

<div class="col-md-3 col-6 mb-4">
  <div
    class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden"
  >
    <!-- Background decoration -->
    <div
      class="absolute inset-0 {{ $colorClasses["light"] }} opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-xl"
    ></div>

    <!-- Icon -->
    <div class="relative z-10 flex items-center justify-between mb-4">
      <div
        class="w-12 h-12 {{ $colorClasses["light"] }} rounded-xl flex items-center justify-center group-hover:scale-110 transition-all duration-300"
      >
        <i class="bi {{ $icon }} text-xl {{ $colorClasses["text"] }}"></i>
      </div>
    </div>

    <!-- Content -->
    <div class="relative z-10">
      <h3 class="text-sm font-medium text-gray-900 mb-1">{{ $title }}</h3>
      <div class="text-2xl lg:text-3xl font-bold text-secondary mb-1">
        {{ $value }}
      </div>
    </div>

    <!-- Hover effect overlay -->
    <div
      class="absolute bottom-0 left-0 right-0 h-1 {{ $colorClasses["bg"] }} transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 rounded-b-xl"
    ></div>
  </div>
</div>
