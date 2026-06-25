<section class="px-6 py-16 md:py-24">
  <div class="mx-auto max-w-6xl">
    @if (filled($kit['title'] ?? null))
      <h2
        class="mb-4 font-display text-3xl leading-tight text-neutral-black uppercase md:text-5xl"
      >
        {{ $kit['title'] }}
      </h2>
    @endif

    @if (filled($kit['description'] ?? null))
      <p class="mb-10 max-w-3xl font-sans text-base leading-relaxed text-neutral-black md:mb-12 md:text-lg">
        {{ $kit['description'] }}
      </p>
    @endif

    @if (filled($kit['items'] ?? null))
      <ul
        class="mb-10 grid gap-4 md:mb-12 md:gap-6"
        style="grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));"
      >
        @foreach ($kit['items'] as $item)
          <li class="flex flex-col gap-2">
            <div
              class="flex aspect-square items-center justify-center border-2 border-neutral-black bg-white p-3 shadow-neo"
            >
              @if ($item['isImage'] && filled($item['thumbUrl'] ?? null))
                <img
                  src="{{ esc_url($item['thumbUrl']) }}"
                  alt="{{ esc_attr($item['alt'] ?? $item['filename']) }}"
                  class="max-h-full max-w-full object-contain"
                  loading="lazy"
                  decoding="async"
                />
              @else
                <div class="text-center">
                  <p class="font-display text-2xl uppercase text-neutral-black">
                    {{ $item['extension'] }}
                  </p>
                  <p class="mt-2 line-clamp-2 font-sans text-xs text-neutral-black">
                    {{ $item['filename'] }}
                  </p>
                </div>
              @endif
            </div>

            <a
              href="{{ esc_url($item['url']) }}"
              class="stp-footer-link text-xs"
              download
            >
              {{ stp_pll__('Télécharger') }}
              @if (filled($item['filesizeLabel'] ?? null))
                <span class="font-normal">({{ $item['filesizeLabel'] }})</span>
              @endif
            </a>
          </li>
        @endforeach
      </ul>
    @endif

    @if (filled($kit['zipUrl'] ?? null))
      <x-button color="blue" :href="$kit['zipUrl']" download>
        {{ $kit['zipLabel'] }}
      </x-button>
    @endif
  </div>
</section>
