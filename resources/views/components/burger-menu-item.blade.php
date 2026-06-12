@props([
  'item',
  'depth' => 0,
  'index' => 0,
])

@php
  $classes = is_array($item->classes ?? null) ? $item->classes : [];
  $isCurrentPage = in_array('current-menu-item', $classes, true);
  $isCurrentSection = in_array('current-menu-ancestor', $classes, true)
    || in_array('current-menu-parent', $classes, true);
  $isBlueAccent = $index % 2 === 0;
@endphp

<li @if ($depth === 0) data-stp-nav-item @endif>
  @if ($depth > 0)
    <a
      href="{{ esc_url($item->url) }}"
      class="inline-flex w-full rounded-sm px-3 py-2 font-sans text-base font-bold text-neutral-black underline-offset-2 transition-all duration-150 hover:translate-x-0.5 hover:bg-brand-pink/40 hover:underline focus-visible:ring-2 focus-visible:ring-neutral-black focus-visible:ring-offset-2 focus-visible:outline-none"
      @if ($isCurrentPage) aria-current="page" @endif
    >
      {{ esc_html($item->title) }}
    </a>
  @elseif ($isBlueAccent)
    <a
      href="{{ esc_url($item->url) }}"
      class="flex w-full border-2 px-4 py-3 font-display text-3xl leading-none tracking-wide text-neutral-black uppercase transition-all duration-150 hover:translate-x-0.5 hover:translate-y-0.5 focus-visible:ring-2 focus-visible:ring-neutral-black focus-visible:ring-offset-2 focus-visible:outline-none md:text-4xl {{ $isCurrentPage || $isCurrentSection ? 'border-neutral-black bg-brand-blue shadow-neo-sm' : 'border-transparent hover:border-neutral-black hover:bg-brand-blue hover:shadow-neo-sm focus-visible:border-neutral-black' }}"
      @if ($isCurrentPage) aria-current="page" @endif
    >
      {{ esc_html($item->title) }}
    </a>
  @else
    <a
      href="{{ esc_url($item->url) }}"
      class="flex w-full border-2 px-4 py-3 font-display text-3xl leading-none tracking-wide text-neutral-black uppercase transition-all duration-150 hover:translate-x-0.5 hover:translate-y-0.5 focus-visible:ring-2 focus-visible:ring-neutral-black focus-visible:ring-offset-2 focus-visible:outline-none md:text-4xl {{ $isCurrentPage || $isCurrentSection ? 'border-neutral-black bg-brand-pink shadow-neo-sm' : 'border-transparent hover:border-neutral-black hover:bg-brand-pink hover:shadow-neo-sm focus-visible:border-neutral-black' }}"
      @if ($isCurrentPage) aria-current="page" @endif
    >
      {{ esc_html($item->title) }}
    </a>
  @endif

  @if (! empty($item->children))
    <ul class="mx-2 mb-2 flex flex-col gap-1 border-2 border-neutral-black/10 bg-neutral-beige p-2">
      @foreach ($item->children as $childIndex => $child)
        <x-burger-menu-item :item="$child" :depth="$depth + 1" :index="$childIndex" />
      @endforeach
    </ul>
  @endif
</li>
