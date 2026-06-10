{{--
  Template Name: En construction
--}}

@extends ('layouts.coming-soon')

@section ('content')
  @while (have_posts())
    @php (the_post())
    <x-construction-notice />
    @include ('partials.content-page')
  @endwhile
@endsection
