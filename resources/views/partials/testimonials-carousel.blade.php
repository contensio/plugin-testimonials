@php
    use Contensio\Testimonials\Models\Testimonial;

    $limit      = isset($limit)      ? (int) $limit      : 10;
    $autoplay   = isset($autoplay)   ? (bool) $autoplay  : true;
    $interval   = isset($interval)   ? (int) $interval   : 5000; // ms
    $showRating = isset($showRating) ? (bool) $showRating : true;

    try {
        $items = Testimonial::approved()->latest()->limit($limit)->get();
    } catch (\Throwable) {
        $items = collect();
    }

    if ($items->isEmpty()) { return; }

    $itemsJson = $items->map(fn ($t) => [
        'name'       => $t->name,
        'byline'     => $t->byline(),
        'content'    => $t->content,
        'rating'     => $t->rating,
        'avatar_url' => $t->avatar_url,
        'initials'   => $t->initials(),
        'color'      => $t->avatarColor(),
    ])->values()->toJson();
@endphp

<div class="testimonials-carousel"
     x-data="testimonialsCarousel({{ $itemsJson }}, {{ $autoplay ? 'true' : 'false' }}, {{ $interval }})"
     @mouseenter="pause"
     @mouseleave="resume">

    {{-- Slide --}}
    <div class="relative overflow-hidden">
        <div class="bg-white border border-gray-200 rounded-2xl p-8 md:p-10 text-center max-w-2xl mx-auto">

            {{-- Stars --}}
            @if($showRating)
            <div class="flex items-center justify-center gap-0.5 mb-5 min-h-[20px]">
                <template x-if="current.rating">
                    <div class="flex items-center gap-0.5">
                        <template x-for="s in 5" :key="s">
                            <i class="bi text-base leading-none"
                               :class="s <= current.rating ? 'bi-star-fill text-amber-400' : 'bi-star text-gray-200'"></i>
                        </template>
                    </div>
                </template>
            </div>
            @endif

            {{-- Quote --}}
            <blockquote class="text-lg md:text-xl text-ink-700 leading-relaxed mb-8"
                        x-text="'\"' + current.content + '\"'"></blockquote>

            {{-- Avatar + name --}}
            <div class="flex items-center justify-center gap-3">
                <template x-if="current.avatar_url">
                    <img :src="current.avatar_url" :alt="current.name"
                         class="w-12 h-12 rounded-full object-cover shrink-0">
                </template>
                <template x-if="!current.avatar_url">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-sm font-bold shrink-0"
                         :class="current.color"
                         x-text="current.initials"></div>
                </template>
                <div class="text-left">
                    <p class="font-semibold text-ink-900" x-text="current.name"></p>
                    <p x-show="current.byline" class="text-sm text-ink-500" x-text="current.byline"></p>
                </div>
            </div>
        </div>
    </div>

    {{-- Controls --}}
    @if($items->count() > 1)
    <div class="flex items-center justify-center gap-4 mt-6">

        {{-- Prev --}}
        <button @click="prev" type="button"
                class="w-9 h-9 rounded-full border border-gray-200 bg-white hover:bg-gray-50 text-gray-500 hover:text-gray-700 flex items-center justify-center transition-colors focus:outline-none">
            <i class="bi bi-chevron-left text-sm"></i>
        </button>

        {{-- Dots --}}
        <div class="flex items-center gap-2">
            <template x-for="(_, i) in items" :key="i">
                <button type="button" @click="goTo(i)"
                        class="rounded-full transition-all focus:outline-none"
                        :class="i === index
                            ? 'w-6 h-2.5 bg-ember-500'
                            : 'w-2.5 h-2.5 bg-gray-300 hover:bg-gray-400'">
                </button>
            </template>
        </div>

        {{-- Next --}}
        <button @click="next" type="button"
                class="w-9 h-9 rounded-full border border-gray-200 bg-white hover:bg-gray-50 text-gray-500 hover:text-gray-700 flex items-center justify-center transition-colors focus:outline-none">
            <i class="bi bi-chevron-right text-sm"></i>
        </button>

    </div>
    @endif

</div>

<script>
function testimonialsCarousel(items, autoplay, interval) {
    return {
        items,
        index:   0,
        timer:   null,

        get current() { return this.items[this.index]; },

        goTo(i) {
            this.index = i;
            if (autoplay) { this.resetTimer(); }
        },

        next() { this.goTo((this.index + 1) % this.items.length); },
        prev() { this.goTo((this.index - 1 + this.items.length) % this.items.length); },

        pause()  { clearInterval(this.timer); this.timer = null; },
        resume() { if (autoplay && ! this.timer) this.startTimer(); },

        startTimer() {
            this.timer = setInterval(() => this.next(), interval);
        },

        resetTimer() {
            clearInterval(this.timer);
            this.timer = null;
            if (autoplay) this.startTimer();
        },

        init() { if (autoplay && this.items.length > 1) this.startTimer(); },
    };
}
</script>
