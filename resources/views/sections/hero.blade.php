<section class="px-6 py-16 md:py-24 h-[80dvh] flex flex-col items-center justify-center">
  <div class="mx-auto max-w-4xl text-center">
    @if ($hero['title'] ?? null)
      <h1 class="mb-6 text-4xl uppercase font-display leading-tight text-neutral-black md:text-7xl md:leading-none">
        {!! nl2br(e($hero['title'])) !!}
      </h1>
    @endif

    @if ($hero['description'] ?? null)
      <p class="mx-auto mb-10 max-w-2xl font-sans text-md font-normal">
        {!! nl2br(e($hero['description'])) !!}
      </p>
    @endif

    @php
      $filteredButtons = collect($hero['buttons'] ?? [])->filter(fn ($button) => filled($button['label'] ?? null) && filled($button['url'] ?? null));
    @endphp

    @if ($filteredButtons->isNotEmpty())
      <div class="flex flex-wrap justify-center gap-4">
        @foreach ($filteredButtons as $button)
          <x-button :color="$button['color'] ?? 'beige'" :href="$button['url']" active>
            {{ $button['label'] }}
          </x-button>
        @endforeach
      </div>
    @endif
  </div>
</section>
