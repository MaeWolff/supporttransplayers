@extends ('layouts.app')

@section ('content')
  @include ('partials.page-header')

  @if (! have_posts())
    <x-alert type="warning">
      {!! stp_pll__('Sorry, but the page you are trying to view does not exist.') !!}
    </x-alert>
    {!! get_search_form(false) !!}
  @endif
@endsection
