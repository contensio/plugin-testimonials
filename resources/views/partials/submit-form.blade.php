@php
    $submitted = session('testimonial_submitted');
@endphp

<div class="testimonials-submit-form">

    @if($submitted)
    <div class="rounded-xl bg-green-50 border border-green-200 px-6 py-8 text-center">
        <i class="bi bi-check-circle text-3xl text-green-500 mb-3 block"></i>
        <p class="font-semibold text-green-800 text-lg">Thank you for your testimonial!</p>
        <p class="text-green-700 text-sm mt-1">It will appear on the site after review.</p>
    </div>
    @else

    @if($errors->any())
    <div class="mb-5 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-800 text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('contensio-testimonials.submit') }}" class="space-y-5"
          x-data="{ rating: {{ old('rating', 0) }} }">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label for="tm_name" class="block text-sm font-medium text-ink-800 mb-1">
                    Your name <span class="text-red-500">*</span>
                </label>
                <input id="tm_name" name="name" type="text" required maxlength="150"
                       value="{{ old('name') }}"
                       class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-base focus:outline-none focus:ring-2 focus:ring-ember-400"
                       placeholder="Jane Smith">
            </div>
            <div>
                <label for="tm_role" class="block text-sm font-medium text-ink-800 mb-1">
                    Role / Title <span class="text-ink-400 font-normal">(optional)</span>
                </label>
                <input id="tm_role" name="role" type="text" maxlength="150"
                       value="{{ old('role') }}"
                       class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-base focus:outline-none focus:ring-2 focus:ring-ember-400"
                       placeholder="CEO">
            </div>
        </div>

        <div>
            <label for="tm_company" class="block text-sm font-medium text-ink-800 mb-1">
                Company <span class="text-ink-400 font-normal">(optional)</span>
            </label>
            <input id="tm_company" name="company" type="text" maxlength="150"
                   value="{{ old('company') }}"
                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-base focus:outline-none focus:ring-2 focus:ring-ember-400"
                   placeholder="Acme Corp">
        </div>

        <div>
            <label class="block text-sm font-medium text-ink-800 mb-2">
                Rating <span class="text-ink-400 font-normal">(optional)</span>
            </label>
            <div class="flex items-center gap-1">
                @for($s = 1; $s <= 5; $s++)
                <button type="button"
                        @click="rating = {{ $s }} === rating ? 0 : {{ $s }}"
                        :title="'{{ $s }} star{{ $s !== 1 ? 's' : '' }}'"
                        class="text-2xl leading-none transition-colors focus:outline-none">
                    <i class="bi bi-star-fill"
                       :class="{{ $s }} <= rating ? 'text-amber-400' : 'text-gray-200'"></i>
                </button>
                @endfor
                <input type="hidden" name="rating" :value="rating || ''">
                <button type="button" x-show="rating > 0" @click="rating = 0"
                        class="ml-2 text-xs text-gray-400 hover:text-gray-600 transition-colors" x-cloak>
                    Clear
                </button>
            </div>
        </div>

        <div>
            <label for="tm_content" class="block text-sm font-medium text-ink-800 mb-1">
                Your testimonial <span class="text-red-500">*</span>
            </label>
            <textarea id="tm_content" name="content" rows="5" required minlength="10" maxlength="2000"
                      class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-base focus:outline-none focus:ring-2 focus:ring-ember-400 resize-y"
                      placeholder="Share your experience…">{{ old('content') }}</textarea>
            <p class="mt-1 text-xs text-ink-400">Minimum 10 characters. Your testimonial will appear after review.</p>
        </div>

        <button type="submit"
                class="inline-flex items-center gap-2 bg-ember-500 hover:bg-ember-600 text-white font-semibold px-6 py-3 rounded-xl transition-colors">
            Submit testimonial
        </button>

    </form>
    @endif

</div>
