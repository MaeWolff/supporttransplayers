@props ([
  'credit' => null,
])

@if (filled($credit['intro'] ?? null))
  <p class="stp-footer-credit max-w-2xl text-pretty">
    {{ $credit['intro'] }}
    @if (filled($credit['transpireUrl'] ?? null))
      <a
        href="{{ esc_url($credit['transpireUrl']) }}"
        class="stp-footer-link-inline"
        target="_blank"
        rel="noopener noreferrer"
      >
        TRANSpire
      </a>
    @else
      <span class="font-bold">TRANSpire</span>
    @endif
    {{ $credit['joiner'] }}
    @if (filled($credit['plaidactUrl'] ?? null))
      <a
        href="{{ esc_url($credit['plaidactUrl']) }}"
        class="stp-footer-link-inline"
        target="_blank"
        rel="noopener noreferrer"
      >
        PLAID•ACT
      </a>
    @else
      <span class="font-bold">PLAID•ACT</span>
    @endif
    .
  </p>
@endif
