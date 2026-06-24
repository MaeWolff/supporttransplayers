<footer
  role="contentinfo"
  class="mt-auto border-t-2 border-neutral-black bg-white"
  data-stp-footer
>
  <div class="mx-auto max-w-7xl px-6 py-10 md:py-12">
    @if ($footer['showLogo'] ?? false)
      <div class="mb-10 md:mb-12">
        @if (filled($footer['horizontalLogo']['url'] ?? null))
          <img
            src="{{ esc_url($footer['horizontalLogo']['url']) }}"
            alt="{{ esc_attr($footer['horizontalLogo']['alt'] ?? '') }}"
            class="h-auto max-h-12 w-auto max-w-full object-contain object-left md:max-h-14"
            decoding="async"
          />
        @endif
      </div>
    @endif

    @if ($footer['showMiddleBand'] ?? false)
      <div
        @class ([
          'mb-10 grid grid-cols-[repeat(auto-fit,minmax(11rem,1fr))] gap-8 md:mb-12 md:gap-10',
          'md:mb-12' => $footer['showCredit'] ?? false,
        ])
      >
        @if ($footer['showContact'] ?? false)
          <div>
            <p class="mb-3 font-sans text-xs font-bold tracking-wide text-neutral-black uppercase">
              {{ $footer['contactLabel'] }}
            </p>
            <a
              href="mailto:{{ $footer['contactEmail'] }}"
              class="stp-footer-link text-base"
            >
              {{ $footer['contactEmail'] }}
            </a>
          </div>
        @endif

        @if ($footer['showMenu'] ?? false)
          <div>
            <p class="mb-3 font-sans text-xs font-bold tracking-wide text-neutral-black uppercase">
              {{ stp_pll__('Navigation') }}
            </p>
            <nav aria-label="{{ stp_pll__('Footer Navigation') }}">
              <ul class="flex flex-col gap-1.5">
                @foreach ($footer['menuItems'] as $item)
                  <li>
                    <a href="{{ esc_url($item->url) }}" class="stp-footer-link">
                      {{ esc_html($item->title) }}
                    </a>
                  </li>
                @endforeach
              </ul>
            </nav>
          </div>
        @endif

        @if ($footer['showLegal'] ?? false)
          <div>
            <p class="mb-3 font-sans text-xs font-bold tracking-wide text-neutral-black uppercase">
              {{ stp_pll__('Mentions légales') }}
            </p>
            <a
              href="{{ esc_url($footer['legalUrl']) }}"
              class="stp-footer-link"
            >
              {{ $footer['legalTitle'] }}
            </a>
          </div>
        @endif

        @if ($footer['showSocial'] ?? false)
          <div>
            <p class="mb-3 font-sans text-xs font-bold tracking-wide text-neutral-black uppercase">
              {{ stp_pll__('Suivez-nous') }}
            </p>
            <ul class="flex flex-wrap gap-x-4 gap-y-1.5">
              @foreach ($footer['socialLinks'] as $link)
                <li>
                  <a
                    href="{{ esc_url($link['url']) }}"
                    class="stp-footer-link"
                    target="_blank"
                    rel="noopener noreferrer"
                  >
                    {{ filled($link['platform'] ?? null) ? ucfirst($link['platform']) : $link['label'] }}
                  </a>
                </li>
              @endforeach
            </ul>
          </div>
        @endif

        @if ($footer['showLanguage'] ?? false)
          <div>
            <p class="mb-3 font-sans text-xs font-bold tracking-wide text-neutral-black uppercase">
              {{ stp_pll__('Langue') }}
            </p>
            <x-language-switcher />
          </div>
        @endif
      </div>
    @endif

    @if ($footer['showCredit'] ?? false)
      <div class="space-y-4 text-sm leading-relaxed text-neutral-black">
        @if (filled($footer['campaignCredit'] ?? null))
          <x-footer-campaign-credit :credit="$footer['campaignCredit']" />
        @endif

        @if (filled($footer['copyright'] ?? null))
          <p class="font-bold md:text-right">{{ $footer['copyright'] }}</p>
        @endif
      </div>
    @endif
  </div>
</footer>
