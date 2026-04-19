@php
    use Contensio\Testimonials\Models\Testimonial;
    try {
        $approvedCount = Testimonial::approved()->count();
        $pendingCount  = Testimonial::pending()->count();
    } catch (\Throwable) {
        $approvedCount = null;
        $pendingCount  = null;
    }
@endphp
<a href="{{ route('testimonials.index') }}"
   class="block bg-white border border-gray-200 rounded-xl p-5 hover:border-ember-400 hover:shadow-sm transition-all group">
    <div class="flex items-start justify-between gap-3">
        <div class="w-10 h-10 rounded-lg bg-ember-500/10 text-ember-600 flex items-center justify-center text-xl shrink-0">
            <i class="bi bi-chat-quote"></i>
        </div>
        @if($pendingCount !== null && $pendingCount > 0)
        <span class="text-sm font-semibold text-amber-600 bg-amber-50 border border-amber-200 rounded-full px-2.5 py-0.5">
            {{ $pendingCount }} pending
        </span>
        @elseif($approvedCount !== null)
        <span class="text-sm font-semibold text-gray-400">{{ $approvedCount }} published</span>
        @endif
    </div>
    <p class="mt-3 font-semibold text-gray-900 group-hover:text-ember-600 transition-colors">Testimonials</p>
    <p class="mt-0.5 text-sm text-gray-500">Collect and display social proof.</p>
</a>
