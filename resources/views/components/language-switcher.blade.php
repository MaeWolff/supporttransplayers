@props([
  'languages' => null,
])

@php
  $languages = $languages ?? stp_languages();
  $current = collect($languages)->first(fn (array $lang): bool => (bool) ($lang['current_lang'] ?? false));
  $currentSlug = strtoupper((string) ($current['slug'] ?? ($languages[0]['slug'] ?? '')));
  $menuId = 'stp-lang-menu-'.wp_unique_id();
@endphp

@if ($languages !== [])
  <div
    {{ $attributes->merge(['class' => 'relative']) }}
    data-stp-lang-switcher
  >
    {{-- Mobile: langue active + menu --}}
    <div class="md:hidden">
      <button
        type="button"
        class="btn-neo group inline-flex cursor-pointer items-center gap-1.5 bg-brand-blue px-2 text-lg text-neutral-black"
        data-stp-lang-toggle
        aria-expanded="false"
        aria-haspopup="listbox"
        aria-controls="{{ $menuId }}"
        aria-label="{{ stp_pll__('Language switcher') }}"
      >
        <span>{{ $currentSlug }}</span>
        <svg
          class="size-3.5 shrink-0 transition-transform duration-200 ease-[cubic-bezier(0.16,1,0.3,1)] group-aria-expanded:rotate-180 motion-reduce:transition-none"
          viewBox="0 0 12 12"
          fill="none"
          aria-hidden="true"
        >
          <path
            d="M2 4.5L6 8.5L10 4.5"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="square"
          />
        </svg>
      </button>

      <ul
        id="{{ $menuId }}"
        class="absolute top-full right-0 z-50 mt-2 flex min-w-[4.5rem] flex-col gap-2 border-2 border-neutral-black bg-white p-2 shadow-neo"
        role="listbox"
        data-stp-lang-menu
        aria-label="{{ stp_pll__('Language switcher') }}"
        hidden
      >
        @foreach ($languages as $lang)
          @php
            $slug = strtoupper((string) $lang['slug']);
            $isCurrent = (bool) ($lang['current_lang'] ?? false);
          @endphp
          <li role="option" aria-selected="{{ $isCurrent ? 'true' : 'false' }}">
            @if ($isCurrent)
              <span
                class="btn-neo inline-flex w-full cursor-default items-center justify-center bg-brand-blue px-2 text-lg text-neutral-black"
                aria-current="true"
              >
                {{ $slug }}
              </span>
            @else
              <a
                href="{{ $lang['url'] }}"
                hreflang="{{ $lang['slug'] }}"
                class="btn-neo inline-flex w-full cursor-pointer items-center justify-center bg-white px-2 text-lg text-neutral-black"
              >
                {{ $slug }}
              </a>
            @endif
          </li>
        @endforeach
      </ul>
    </div>

    {{-- Desktop: toutes les langues --}}
    <ul
      class="hidden flex-wrap gap-4 md:flex"
      role="list"
      aria-label="{{ stp_pll__('Language switcher') }}"
    >
      @foreach ($languages as $lang)
        <li>
          @if ($lang['current_lang'] ?? false)
            <span aria-current="true">
              <x-button size="sm" color="blue" active class="cursor-default">
                {{ strtoupper($lang['slug']) }}
              </x-button>
            </span>
          @else
            <x-button
              size="sm"
              color="beige"
              :href="$lang['url']"
              hreflang="{{ $lang['slug'] }}"
            >
              {{ strtoupper($lang['slug']) }}
            </x-button>
          @endif
        </li>
      @endforeach
    </ul>
  </div>
@endif
