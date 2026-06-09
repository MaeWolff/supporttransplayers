<header class="mt-6 z-20 bg-white">
  <div class="mx-auto grid max-w-7xl grid-cols-[1fr_auto_1fr] items-start px-6 py-4">
    <div aria-hidden="true"></div>

    <a href="{{ home_url('/') }}" aria-label="{{ $siteName }}" class="inline-block shrink-0">
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

    <div class="flex justify-end gap-4">
      <x-button size="sm" color="blue" active>FR</x-button>
      <x-button size="sm" color="beige">EN</x-button>
    </div>
  </div>
</header>
