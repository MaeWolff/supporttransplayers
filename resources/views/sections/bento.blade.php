<section class="px-6 py-16 md:py-24">
  <div class="mx-auto max-w-6xl">
    @if (filled($bento['sectionTitle'] ?? null))
      <h2
        class="mb-10 text-center font-display text-3xl leading-tight text-neutral-black uppercase md:text-5xl"
      >
        {{ $bento['sectionTitle'] }}
      </h2>
    @endif

    @if (filled($bento['items'] ?? null))
      <div class="grid grid-cols-1 gap-4 md:grid-cols-3 md:gap-6">
        @foreach ($bento['items'] as $item)
          <x-bento-card
            :title="$item['title'] ?? ''"
            :body="$item['body'] ?? ''"
            :url="$item['url'] ?? ''"
            :link-label="$item['linkLabel'] ?? ''"
            :color="$item['color'] ?? 'pink'"
            :size="$item['size'] ?? 'medium'"
            :external="$item['external'] ?? false"
          />
        @endforeach
      </div>
    @endif
  </div>
</section>
