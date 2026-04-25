@props([
    'active' => false,
    'tab' => '',
    'target' => null,
    'onclick' => null,
    'badgeId' => null,
    'badgeValue' => null,
    'type' => 'button',
])

@php
    $classes = 'tab-btn px-4 py-2.5 rounded-md text-sm font-semibold transition-all duration-200';
    if ($active) {
        $classes .= ' active';
    }
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge(['class' => $classes]) }}
    @if($tab !== '') data-tab="{{ $tab }}" @endif
    @if($target) data-tab-target="{{ $target }}" @endif
    @if($onclick) onclick="{{ $onclick }}" @endif
    role="tab"
    aria-selected="{{ $active ? 'true' : 'false' }}"
>
    <span>{{ $slot }}</span>
    @if($badgeId)
        <span id="{{ $badgeId }}" class="tab-badge {{ $badgeValue ? '' : 'hidden' }}">{{ $badgeValue ?? '0' }}</span>
    @endif
</button>
