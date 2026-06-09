<form
  role="search"
  method="get"
  class="search-form"
  action="{{ home_url('/') }}"
>
  <label>
    <span class="sr-only"> {{ stp_pll_x('Search for:', 'label') }} </span>

    <input
      type="search"
      placeholder="{!! esc_attr(stp_pll_x('Search &hellip;', 'placeholder')) !!}"
      value="{!! get_search_query() !!}"
      name="s"
    />
  </label>

  <button>{{ stp_pll_x('Search', 'submit button') }}</button>
</form>
