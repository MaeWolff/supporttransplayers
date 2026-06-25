<section class="px-6 py-12 md:py-16">
  <div class="mx-auto max-w-6xl">
    @if (filled($resources['sectionTitle'] ?? null))
      <h2
        class="mb-8 font-display text-2xl leading-tight text-neutral-black uppercase md:mb-10 md:text-3xl"
      >
        {{ $resources['sectionTitle'] }}
      </h2>
    @endif

    @if (filled($resources['items'] ?? null))
      <ul class="flex flex-col gap-3" role="list">
        @foreach ($resources['items'] as $item)
          <li class="w-full">
            <article
              class="flex w-full flex-col gap-3 border-2 border-neutral-black bg-white px-4 py-3 shadow-neo sm:flex-row sm:items-center sm:justify-between sm:gap-6 md:px-5 md:py-3.5"
            >
              <div class="min-w-0 flex-1">
                <h3 class="font-display text-lg leading-tight uppercase text-neutral-black md:text-xl">
                  {{ $item['title'] }}
                </h3>

                @if (filled($item['description'] ?? null))
                  <p class="mt-1 max-w-3xl font-sans text-sm leading-snug text-neutral-black">
                    {{ $item['description'] }}
                  </p>
                @endif

                @if (filled($item['extension'] ?? null) || filled($item['filesizeLabel'] ?? null))
                  <p class="mt-1 font-sans text-xs text-neutral-black">
                    @if (filled($item['extension'] ?? null))
                      <span>{{ $item['extension'] }}</span>
                    @endif
                    @if (filled($item['extension'] ?? null) && filled($item['filesizeLabel'] ?? null))
                      <span aria-hidden="true"> · </span>
                    @endif
                    @if (filled($item['filesizeLabel'] ?? null))
                      <span>{{ $item['filesizeLabel'] }}</span>
                    @endif
                  </p>
                @endif
              </div>

              <x-button
                size="sm"
                color="blue"
                :href="$item['url']"
                download
                class="shrink-0 self-start sm:self-center"
              >
                {{ stp_pll__('Télécharger') }}
              </x-button>
            </article>
          </li>
        @endforeach
      </ul>
    @endif
  </div>
</section>
