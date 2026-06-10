@props ([
  'title' => '',
  'body' => '',
  'url' => '',
  'linkLabel' => '',
  'color' => 'pink',
  'size' => 'medium',
  'external' => false,
])

@php
  $bgClass = match ($color) {
    'blue' => 'bg-brand-blue',
    'beige' => 'bg-neutral-beige',
    'white' => 'bg-white',
    default => 'bg-brand-pink',
  };

  $spanClass = match ($size) {
    'large' => 'md:col-span-2',
    default => 'md:col-span-1',
  };

  $titleClass = $size === 'large'
    ? 'font-display text-2xl uppercase text-neutral-black md:text-4xl'
    : 'font-display text-2xl uppercase text-neutral-black md:text-3xl';
@endphp

<article
  {{ $attributes->merge(['class' => "flex h-full flex-col border-2 border-neutral-black p-6 shadow-neo md:p-8 {$bgClass} {$spanClass}"]) }}
>
  @if (filled($title))
    <h3 class="{{ $titleClass }}">{{ $title }}</h3>
  @endif

  @if (filled($body))
    <p class="mt-3 flex-1 font-sans text-base leading-relaxed text-neutral-black">
      {{ $body }}
    </p>
  @endif

  @if (filled($url))
    <a
      href="{{ esc_url($url) }}"
      class="mt-4 inline-block font-bold text-brand-blue underline-offset-2 hover:underline"
      @if ($external) target="_blank" rel="noopener noreferrer" @endif
    >
      {{ filled($linkLabel) ? $linkLabel : stp_pll__('En savoir plus') }}
    </a>
  @endif
</article>
