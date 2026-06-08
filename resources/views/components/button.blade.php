@props([
  'size' => 'default',
  'color' => 'black',
  'active' => false,
  'href' => null,
  'type' => 'button',
])

@php
  $sizeClass = match ($size) {
    'sm' => 'px-4 py-2 text-sm',
    default => 'px-6 py-3 text-base',
  };

  $colorClass = $active
    ? match ($color) {
        'pink' => 'bg-brand-pink text-neutral-black',
        'blue' => 'bg-brand-blue text-neutral-black',
        default => 'bg-neutral-black text-neutral-beige',
      }
    : 'bg-white text-neutral-black';

  $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }}
  @if ($href) href="{{ $href }}" @else type="{{ $type }}" @endif
  {{ $attributes->merge(['class' => "btn-neo cursor-pointer {$sizeClass} {$colorClass}"]) }}
>
  {{ $slot }}
</{{ $tag }}>
