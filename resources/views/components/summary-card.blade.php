@props([
    'label',
    'value' => '0',
    'accent' => 'gray',
    'valueClass' => '',
    'id' => null,
])

@php
    $accentClasses = [
        'gray' => 'border-gray-200',
        'blue' => 'border-blue-200',
        'green' => 'border-green-200',
        'amber' => 'border-amber-200',
        'red' => 'border-red-200',
        'indigo' => 'border-indigo-200',
    ];

    $valueColors = [
        'gray' => 'text-gray-900',
        'blue' => 'text-blue-600',
        'green' => 'text-green-600',
        'amber' => 'text-amber-600',
        'red' => 'text-red-600',
        'indigo' => 'text-indigo-600',
    ];

    $cardAccentClass = $accentClasses[$accent] ?? $accentClasses['gray'];
    $valueClassName = trim(($valueClass ?: ($valueColors[$accent] ?? $valueColors['gray'])) . ' text-2xl font-bold');
@endphp

<div {{ $attributes->merge(['class' => "summary-card {$cardAccentClass}"]) }}>
    <p class="text-xs font-semibold uppercase text-gray-500">{{ $label }}</p>
    <p @if($id) id="{{ $id }}" @endif class="mt-2 {{ $valueClassName }}">{{ $value }}</p>
</div>
