@php
  $menuItems = stp_nav_menu_tree('primary_navigation');
  $contactEmail = 'contact@supporttransplayers.org';
@endphp

<div class="relative z-50" data-stp-burger>
  <button
    type="button"
    class="btn-neo group size-12 cursor-pointer bg-white px-0 py-0 aria-expanded:bg-brand-pink"
    aria-expanded="false"
    aria-controls="stp-nav-drawer"
    data-stp-burger-toggle
  >
    <span class="sr-only" data-stp-burger-label>{{ stp_pll__('Open menu') }}</span>
    <span class="flex w-5 flex-col gap-[5px]" aria-hidden="true">
      <span
        class="block h-[3px] w-full origin-center bg-neutral-black transition-transform duration-200 ease-[cubic-bezier(0.16,1,0.3,1)] group-aria-expanded:translate-y-2 group-aria-expanded:rotate-45 motion-reduce:transition-none"
      ></span>
      <span
        class="block h-[3px] w-full origin-center bg-neutral-black transition-all duration-200 ease-[cubic-bezier(0.16,1,0.3,1)] group-aria-expanded:scale-x-0 group-aria-expanded:opacity-0 motion-reduce:transition-none"
      ></span>
      <span
        class="block h-[3px] w-full origin-center bg-neutral-black transition-transform duration-200 ease-[cubic-bezier(0.16,1,0.3,1)] group-aria-expanded:-translate-y-2 group-aria-expanded:-rotate-45 motion-reduce:transition-none"
      ></span>
    </span>
  </button>

  <div
    class="pointer-events-none fixed inset-0 z-30 bg-neutral-black/45 opacity-0"
    data-stp-burger-backdrop
    aria-hidden="true"
  ></div>

  <nav
    id="stp-nav-drawer"
    class="pointer-events-none fixed top-0 left-0 z-40 flex h-dvh w-full flex-col border-r-2 border-neutral-black bg-white shadow-neo md:w-[40%]"
    aria-label="{{ stp_pll__('Primary Navigation') }}"
    data-stp-burger-drawer
    data-close-label="{{ stp_pll__('Close menu') }}"
    inert
    aria-hidden="true"
  >
    <div
      class="relative shrink-0 border-b-2 border-neutral-black bg-brand-pink px-6 pt-24 pb-8 md:px-10 md:pt-28 md:pb-10"
      data-stp-burger-header
    >
      <div class="flex items-start justify-between gap-5 md:gap-8">
        <div class="min-w-0 flex-1">
          <div class="flex items-center gap-3">
            <span
              class="inline-block size-3 shrink-0 border-2 border-neutral-black bg-brand-blue shadow-neo-sm"
              aria-hidden="true"
            ></span>
            <p class="font-sans text-sm font-bold text-neutral-black">
              {{ stp_pll__('Menu') }}
            </p>
          </div>

          <h2
            class="mt-3 max-w-[14ch] font-display text-5xl leading-none tracking-wide text-neutral-black uppercase text-balance md:max-w-none md:text-6xl"
          >
            <span class="box-decoration-clone bg-white px-2 py-1 shadow-neo-sm">
              {{ $siteName }}
            </span>
          </h2>
        </div>

        <button
          type="button"
          class="mt-0.5 flex size-20 shrink-0 cursor-pointer items-center justify-center rounded-full border-2 border-neutral-black bg-white shadow-neo-sm transition-all duration-150 hover:translate-x-0.5 hover:translate-y-0.5 hover:bg-brand-blue hover:shadow-neo focus-visible:ring-2 focus-visible:ring-neutral-black focus-visible:ring-offset-2 focus-visible:outline-none md:mt-1 md:size-24"
          data-stp-burger-close
          aria-label="{{ stp_pll__('Close menu') }}"
        >
          <span class="relative block size-5 md:size-6" aria-hidden="true">
            <span
              class="absolute top-1/2 left-0 block h-[3px] w-full -translate-y-1/2 rotate-45 bg-neutral-black"
            ></span>
            <span
              class="absolute top-1/2 left-0 block h-[3px] w-full -translate-y-1/2 -rotate-45 bg-neutral-black"
            ></span>
          </span>
        </button>
      </div>
    </div>

    <div class="flex min-h-0 flex-1 flex-col overflow-y-auto bg-white px-4 py-6 md:px-8 md:py-8">
      @if ($menuItems !== [])
        <ul data-stp-nav-menu class="flex flex-col gap-2">
          @foreach ($menuItems as $index => $item)
            <x-burger-menu-item :item="$item" :index="$index" />
          @endforeach
        </ul>
      @else
        <p class="px-2 font-sans text-base leading-relaxed text-neutral-black">
          {{ stp_pll__('Assign a menu in Appearance → Menus.') }}
        </p>
      @endif
    </div>

    <div
      class="mt-auto shrink-0 border-t-2 border-neutral-black bg-brand-blue px-6 py-6 md:px-10 md:py-8"
      data-stp-burger-footer
    >
      <p class="font-sans text-sm font-bold text-neutral-black">
        {{ stp_pll__('Contact') }}
      </p>
      <a
        href="mailto:{{ $contactEmail }}"
        class="btn-neo mt-3 inline-flex bg-brand-pink px-3 py-2 font-sans text-base font-bold normal-case tracking-normal hover:bg-white"
      >
        {{ $contactEmail }}
      </a>
    </div>
  </nav>
</div>
