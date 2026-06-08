<section class="px-6 py-16 md:py-24 h-[80dvh] flex flex-col items-center justify-center">
  <div class="mx-auto max-w-4xl text-center">
    @if ($hero['title'])
      <h1 class="mb-6 text-4xl font-black uppercase leading-tight text-neutral-black md:text-6xl">
        {!! nl2br(e($hero['title'])) !!}
      </h1>
    @endif

    @if ($hero['description'])
      <p class="mx-auto mb-10 max-w-2xl text-lg font-medium md:text-xl">
        {!! nl2br(e($hero['description'])) !!}
      </p>
    @endif

    @php
      $buttons = collect($hero['buttons'])->filter(fn ($button) => filled($button['label']) && filled($button['url']));
    @endphp

    @if ($buttons->isNotEmpty())
      <div class="flex flex-wrap justify-center gap-4">
        @foreach ($buttons as $button)
          <x-button :color="$button['color']" :href="$button['url']" active>
            {{ $button['label'] }}
          </x-button>
        @endforeach
      </div>
    @endif
  </div>
</section>
