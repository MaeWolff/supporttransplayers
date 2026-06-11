<section
  class="stp-hero group flex w-full flex-col items-center overflow-x-clip px-6 py-16 md:py-24"
>
  <div class="relative z-10 mx-auto w-full max-w-4xl min-w-0 text-center">
    @if ($hero['title'] ?? null)
      @php
        $title = $hero['title'];
        $phrases = array_values(array_filter(array_map('trim', explode(',', $hero['highlightWords'] ?? ''))));
        usort($phrases, fn (string $a, string $b): int => mb_strlen($b) <=> mb_strlen($a));
        $lines = preg_split('/\r\n|\r|\n/', $title) ?: [$title];
        $highlightIndex = 0;
      @endphp
      <h1
        class="mb-6 font-display text-4xl leading-[0.95] text-balance wrap-break-word text-neutral-black uppercase sm:text-5xl md:text-7xl"
      >
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
                <span
                  class="stp-hero-highlight box-decoration-clone px-0.5"
                  style="transition-delay: calc(200ms + {{ $highlightIndex }} * 120ms)"
                  >{{ $segment['text'] }}</span
                >
                @php $highlightIndex++; @endphp
              @else
                {{ $segment['text'] }}
              @endif
            @endforeach
            @if (! $loop->last)
              <br />
            @endif
          @endforeach
        @endif
      </h1>
    @endif

    @if ($hero['description'] ?? null)
      <p class="text-md mx-auto mb-10 max-w-2xl font-sans font-normal">
        {!! nl2br(e($hero['description'])) !!}
      </p>
    @endif

    @php
      $filteredButtons = collect($hero['buttons'] ?? [])->filter(fn ($button) => filled($button['label'] ?? null) && filled($button['url'] ?? null));
    @endphp

    @if ($filteredButtons->isNotEmpty())
      <div class="flex flex-wrap justify-center gap-4">
        @foreach ($filteredButtons as $button)
          <x-button
            :color="$button['color'] ?? 'beige'"
            :href="$button['url']"
            active
          >
            {{ $button['label'] }}
          </x-button>
        @endforeach
      </div>
    @endif
  </div>
</section>
