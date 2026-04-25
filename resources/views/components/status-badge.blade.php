@props([
    'status' => 'default',
    'text' => null,
])

@php
    $normalized = strtolower(trim($status));
    $classes = [
        'in-stock' => 'bg-green-100 text-green-700',
        'low-stock' => 'bg-amber-100 text-amber-700',
        'out-of-stock' => 'bg-red-100 text-red-700',
        'active' => 'bg-green-100 text-green-700',
        'inactive' => 'bg-gray-100 text-gray-700',
        'suspended' => 'bg-red-100 text-red-700',
        'pending' => 'bg-amber-100 text-amber-700',
        'completed' => 'bg-green-100 text-green-700',
        'default' => 'bg-gray-100 text-gray-700',
    ];

    $label = $text ?: str_replace('-', ' ', ucfirst($normalized));
    $className = $classes[$normalized] ?? $classes['default'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full {$className}"]) }}>
    {{ $label }}
</span>
