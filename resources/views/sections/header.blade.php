<header class="z-20 mt-6 bg-white">
  <div
    class="mx-auto grid max-w-7xl grid-cols-[1fr_auto_1fr] items-start px-6 py-4"
  >
    <div aria-hidden="true"></div>

    <a
      href="{{ function_exists('pll_home_url') ? pll_home_url() : home_url('/') }}"
      aria-label="{{ $siteName }}"
      class="inline-block shrink-0"
    >
      @if (has_custom_logo())
        {!! wp_get_attachment_image((int) get_theme_mod('custom_logo'), 'full', false, [
          'class' => 'w-[132px] h-auto',
          'alt' => $siteName,
          'decoding' => 'async',
        ]) !!}
      @else
        <span class="font-display text-lg uppercase">{{ $siteName }}</span>
      @endif
    </a>

    @if (function_exists('pll_the_languages'))
      <div class="flex justify-end gap-4">
        @foreach (pll_the_languages(['raw' => 1]) as $lang)
          <x-button
            size="sm"
            :color="$lang['current_lang'] ? 'blue' : 'beige'"
            :href="$lang['url']"
            :active="(bool) $lang['current_lang']"
          >
            {{ strtoupper($lang['slug']) }}
          </x-button>
        @endforeach
      </div>
    @else
      <div class="flex justify-end gap-4">
        <x-button size="sm" color="blue" active>FR</x-button>
        <x-button size="sm" color="beige" href="#">EN</x-button>
      </div>
    @endif
  </div>
</header>
