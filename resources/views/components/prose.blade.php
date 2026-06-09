@props (['html' => ''])

@if (filled($html))
  <article class="stp-prose mx-auto max-w-3xl px-6 py-16 md:py-24">
    {!! $html !!}
  </article>
@endif
