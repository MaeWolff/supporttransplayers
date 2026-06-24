@props([
  'languages' => null,
])

@php
  $languages = $languages ?? stp_languages();
@endphp

@if ($languages !== [])
  <ul
    {{ $attributes->merge(['class' => 'flex flex-wrap gap-4']) }}
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
@endif
