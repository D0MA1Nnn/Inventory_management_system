@props([
    'id' => null,
])

<div @if($id) id="{{ $id }}" @endif {{ $attributes->merge(['class' => 'filter-surface']) }}>
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
        <div class="flex-1 min-w-0">
            {{ $search ?? '' }}
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3">
            {{ $filters ?? '' }}
        </div>
    </div>
</div>
