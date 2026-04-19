@php
    use Contensio\Testimonials\Models\Testimonial;

    $limit  = isset($limit)  ? (int) $limit  : 12;
    $cols   = isset($cols)   ? (int) $cols   : 3;   // 1, 2, or 3
    $showRating = isset($showRating) ? (bool) $showRating : true;

    try {
        $items = Testimonial::approved()->latest()->limit($limit)->get();
    } catch (\Throwable) {
        $items = collect();
    }

    if ($items->isEmpty()) { return; }

    $gridClass = match($cols) {
        1       => 'grid-cols-1',
        2       => 'grid-cols-1 sm:grid-cols-2',
        default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
    };
@endphp

<div class="testimonials-grid grid {{ $gridClass }} gap-6">
    @foreach($items as $t)
    <div class="bg-white border border-gray-200 rounded-2xl p-6 flex flex-col gap-4">

        {{-- Stars --}}
        @if($showRating && $t->rating)
        <div class="flex items-center gap-0.5">
            @for($s = 1; $s <= 5; $s++)
            <i class="bi {{ $s <= $t->rating ? 'bi-star-fill text-amber-400' : 'bi-star text-gray-200' }} text-base leading-none"></i>
            @endfor
        </div>
        @endif

        {{-- Quote --}}
        <blockquote class="text-base text-ink-700 leading-relaxed flex-1">
            "{{ $t->content }}"
        </blockquote>

        {{-- Attribution --}}
        <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
            @if($t->avatar_url)
            <img src="{{ $t->avatar_url }}" alt="{{ $t->name }}"
                 class="w-10 h-10 rounded-full object-cover shrink-0">
            @else
            <div class="w-10 h-10 rounded-full {{ $t->avatarColor() }} flex items-center justify-center text-sm font-bold shrink-0">
                {{ $t->initials() }}
            </div>
            @endif
            <div class="min-w-0">
                <p class="font-semibold text-sm text-ink-900 truncate">{{ $t->name }}</p>
                @if($t->byline())
                <p class="text-xs text-ink-500 truncate">{{ $t->byline() }}</p>
                @endif
            </div>
        </div>

    </div>
    @endforeach
</div>
