<section class="px-6 py-16 md:py-24">
  <div class="mx-auto max-w-3xl text-center">
    <h2
      class="mb-8 font-display text-3xl leading-tight text-neutral-black uppercase md:text-5xl"
    >
      {{ stp_pll__('Restez à l\'affût des dernières news') }}
    </h2>

    <form
      class="stp-newsletter-form flex flex-col gap-4 sm:flex-row sm:items-stretch"
      method="post"
      action="#"
    >
      <label class="sr-only" for="stp-newsletter-email">
        {{ stp_pll_x('Adresse e-mail', 'newsletter label') }}
      </label>

      <input
        id="stp-newsletter-email"
        type="email"
        name="email"
        required
        autocomplete="email"
        placeholder="{{ stp_pll_x('Votre adresse e-mail', 'newsletter placeholder') }}"
        class="w-full min-w-0 flex-1 border-2 border-neutral-black bg-white px-4 py-3 font-sans text-base text-neutral-black shadow-neo placeholder:text-neutral-black/60 focus-visible:ring-2 focus-visible:ring-neutral-black focus-visible:ring-offset-2 focus-visible:outline-none"
      />

      <x-button
        type="submit"
        :color="$newsletter['buttonColor'] ?? 'pink'"
        active
        class="shrink-0 sm:self-stretch"
      >
        {{ stp_pll__('S\'inscrire') }}
      </x-button>
    </form>
  </div>
</section>
