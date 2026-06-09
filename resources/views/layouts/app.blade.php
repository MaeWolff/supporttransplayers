<!doctype html>
<html @php(language_attributes())>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php(do_action('get_header'))
    @php(wp_head())

    <link rel="preload" href="{{ Vite::asset('resources/fonts/InstrumentSans-Regular.ttf') }}" as="font" type="font/ttf" crossorigin>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>

  <body @php(body_class('text-neutral-black font-sans bg-white'))>
    @php(wp_body_open())

    <div id="app">
      <a class="sr-only focus:not-sr-only" href="#main">
        {{ stp_pll__('Skip to content') }}
      </a>

      @include('sections.header')

      <main id="main" class="main">
        @yield('content')
      </main>


      @include('sections.footer')
    </div>

    @php(do_action('get_footer'))
    @php(wp_footer())
  </body>
</html>
