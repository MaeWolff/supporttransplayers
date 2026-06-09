@props ([
  'size' => 'default',
  'color' => 'black',
  'active' => false,
  'href' => null,
  'type' => 'button',
])

@php
  $sizeClass = match ($size) {
    'sm' => 'px-2 text-lg',
    default => 'px-2 py-1 text-3xl',
  };

  $colorClass = $active
    ? match ($color) {
        'pink' => 'bg-brand-pink text-neutral-black',
        'blue' => 'bg-brand-blue text-neutral-black',
        'beige' => 'bg-white text-neutral-black',
        default => 'bg-neutral-beige text-neutral-black',
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
