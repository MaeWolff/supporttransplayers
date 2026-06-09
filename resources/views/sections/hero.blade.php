<section class="stp-hero group flex h-[80dvh] flex-col items-center px-6 py-16 md:py-24">
  <div class="relative z-10 mx-auto max-w-4xl text-center">
    @if ($hero['title'] ?? null)
      @php
        $title = $hero['title'];
        $phrases = array_values(array_filter(array_map('trim', explode(',', $hero['highlightWords'] ?? ''))));
        usort($phrases, fn (string $a, string $b): int => mb_strlen($b) <=> mb_strlen($a));
        $lines = preg_split('/\r\n|\r|\n/', $title) ?: [$title];
        $highlightIndex = 0;
      @endphp

      <h1 class="mb-6 text-4xl font-display uppercase leading-tight text-neutral-black md:text-7xl md:leading-none">
        @if ($phrases === [])
          {!! nl2br(e($title)) !!}
        @else
          @foreach ($lines as $line)
            @php
              $segments = [['text' => $line, 'highlight' => false]];

              foreach ($phrases as $phrase) {
                $next = [];

                foreach ($segments as $segment) {
                  if ($segment['highlight']) {
                    $next[] = $segment;

                    continue;
                  }

                  $position = mb_strpos($segment['text'], $phrase);

                  if ($position === false) {
                    $next[] = $segment;

                    continue;
                  }

                  if ($position > 0) {
                    $next[] = [
                      'text' => mb_substr($segment['text'], 0, $position),
                      'highlight' => false,
                    ];
                  }

                  $next[] = ['text' => $phrase, 'highlight' => true];

                  $rest = mb_substr($segment['text'], $position + mb_strlen($phrase));

                  if ($rest !== '') {
                    $next[] = ['text' => $rest, 'highlight' => false];
                  }
                }

                $segments = $next;
              }
            @endphp

            @foreach ($segments as $segment)
              @if ($segment['highlight'])
                <span class="relative inline whitespace-nowrap">
                  <span
                    class="absolute top-1 -left-1.5 -z-10 h-[0.9em] w-0 bg-brand-blue transition-[width] duration-700 ease-[cubic-bezier(0.22,1,0.36,1)] group-[.is-visible]:w-full in-[.editor-styles-wrapper]:w-full"
                    style="transition-delay: calc(200ms + {{ $highlightIndex }} * 120ms)"
                    aria-hidden="true"
                  ></span>
                  <span class="relative z-10 px-0.5">{{ $segment['text'] }}</span>
                </span>
                @php $highlightIndex++; @endphp
              @else
                {{ $segment['text'] }}
              @endif
            @endforeach

            @if (! $loop->last)
              <br>
            @endif
          @endforeach
        @endif
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
