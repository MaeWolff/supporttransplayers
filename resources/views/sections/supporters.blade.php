<section class="px-6 py-16 md:py-24">
  <div class="mx-auto max-w-6xl">
    @if ($supporters['title'] ?? null)
      <h2 class="mb-10 text-center font-display text-3xl uppercase leading-tight text-neutral-black md:text-5xl">
        {{ $supporters['title'] }}
      </h2>
    @endif

    @if (filled($supporters['supporters'] ?? null))
      <ul class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 md:gap-6 ">
        @foreach ($supporters['supporters'] as $supporter)
          <li>
            @if (filled($supporter['url'] ?? null))
              <a
                href="{{ esc_url($supporter['url']) }}"
                class="flex min-h-24 items-center justify-center border-2 border-neutral-black bg-white p-4 shadow-neo transition-transform duration-150 hover:translate-x-0.5 hover:translate-y-0.5 hover:shadow-neo-sm"
                target="_blank"
                rel="noopener noreferrer"
                @if (filled($supporter['name'] ?? null)) aria-label="{{ $supporter['name'] }}" @endif
              >
                {!! $supporter['logo'] !!}
              </a>
            @else
              <div class="flex min-h-24 items-center justify-center border-2 border-neutral-black bg-white p-4 shadow-neo">
                {!! $supporter['logo'] !!}
              </div>
            @endif
          </li>
        @endforeach
      </ul>
    @endif
  </div>
</section>
