<header class="z-20 mt-6 bg-white">
  <div class="mx-auto flex max-w-7xl items-start justify-between px-6 py-4">
    <div aria-hidden="true" class="w-16 shrink-0"></div>

    <a
      href="{{ function_exists('pll_home_url') ? pll_home_url() : home_url('/') }}"
      aria-label="{{ $siteName }}"
      class="inline-block shrink-0"
    >
      @if (has_custom_logo())
        {!! wp_get_attachment_image((int) get_theme_mod('custom_logo'), 'full', false, [
          'class' => 'w-[90px] h-auto',
          'alt' => $siteName,
          'decoding' => 'async',
        ]) !!}
      @else
        <span class="font-display text-lg uppercase">{{ $siteName }}</span>
      @endif
    </a>

    @if (function_exists('pll_the_languages'))
      <div class="flex w-16 shrink-0 justify-end gap-4">
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
      <div class="w-16 shrink-0" aria-hidden="true"></div>
    @endif
  </div>
</header>
