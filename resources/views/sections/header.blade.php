<header class="sticky top-0 z-10 bg-white">
  <div class="mx-auto grid max-w-7xl grid-cols-[1fr_auto_1fr] items-center px-6 py-4">
    <div aria-hidden="true"></div>

    <a href="{{ home_url('/') }}" aria-label="{{ $siteName }}">
      <div class="flex h-14 w-14 items-center justify-center border-2 border-neutral-black bg-brand-pink font-black shadow-neo">
        LOGO
      </div>
    </a>

    <div class="flex justify-end gap-4">
      <x-button size="sm" color="blue" active>FR</x-button>
      <x-button size="sm" color="beige">EN</x-button>
    </div>
  </div>
</header>
