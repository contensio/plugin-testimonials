@extends('contensio::admin.layout')

@section('title', $testimonial ? 'Edit Testimonial' : 'Add Testimonial')

@section('content')
<div class="p-6 max-w-2xl">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('contensio-testimonials.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
            <i class="bi bi-arrow-left text-lg"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">
            {{ $testimonial ? 'Edit Testimonial' : 'Add Testimonial' }}
        </h1>
    </div>

    @if($errors->any())
    <div class="mb-6 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-800 text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST"
          action="{{ $testimonial ? route('contensio-testimonials.update', $testimonial->id) : route('contensio-testimonials.store') }}"
          class="space-y-6">
        @csrf
        @if($testimonial) @method('PUT') @endif

        {{-- Person --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6 space-y-5">
            <h2 class="text-base font-semibold text-gray-900">Person</h2>

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                <input id="name" name="name" type="text" required maxlength="150"
                       value="{{ old('name', $testimonial?->name) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ember-400"
                       placeholder="Jane Smith">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role / Title <span class="text-gray-400 font-normal">(optional)</span></label>
                    <input id="role" name="role" type="text" maxlength="150"
                           value="{{ old('role', $testimonial?->role) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ember-400"
                           placeholder="CEO">
                </div>
                <div>
                    <label for="company" class="block text-sm font-medium text-gray-700 mb-1">Company <span class="text-gray-400 font-normal">(optional)</span></label>
                    <input id="company" name="company" type="text" maxlength="150"
                           value="{{ old('company', $testimonial?->company) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ember-400"
                           placeholder="Acme Corp">
                </div>
            </div>

            <div>
                <label for="avatar_url" class="block text-sm font-medium text-gray-700 mb-1">Avatar URL <span class="text-gray-400 font-normal">(optional)</span></label>
                <input id="avatar_url" name="avatar_url" type="url" maxlength="500"
                       value="{{ old('avatar_url', $testimonial?->avatar_url) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ember-400"
                       placeholder="https://…">
                <p class="mt-1 text-xs text-gray-400">Leave blank to use auto-generated initials avatar.</p>
            </div>
        </div>

        {{-- Testimonial --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6 space-y-5">
            <h2 class="text-base font-semibold text-gray-900">Testimonial</h2>

            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Message <span class="text-red-500">*</span></label>
                <textarea id="content" name="content" rows="5" required maxlength="2000"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ember-400 resize-y"
                          placeholder="Write the testimonial here…">{{ old('content', $testimonial?->content) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Star rating <span class="text-gray-400 font-normal">(optional)</span></label>
                <div class="flex items-center gap-3">
                    @foreach([1, 2, 3, 4, 5] as $star)
                    <label class="flex items-center gap-1.5 cursor-pointer">
                        <input type="radio" name="rating" value="{{ $star }}"
                               class="text-amber-400 border-gray-300 focus:ring-amber-300"
                               {{ old('rating', $testimonial?->rating) == $star ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700">{{ $star }}</span>
                    </label>
                    @endforeach
                    <label class="flex items-center gap-1.5 cursor-pointer text-sm text-gray-400">
                        <input type="radio" name="rating" value=""
                               {{ ! old('rating', $testimonial?->rating) ? 'checked' : '' }}>
                        None
                    </label>
                </div>
            </div>
        </div>

        {{-- Status --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select id="status" name="status"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ember-400">
                @foreach(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $val => $label)
                <option value="{{ $val }}" {{ old('status', $testimonial?->status ?? 'approved') === $val ? 'selected' : '' }}>
                    {{ $label }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center justify-between">
            <a href="{{ route('contensio-testimonials.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
            <button type="submit"
                    class="bg-ember-500 hover:bg-ember-600 text-white font-semibold px-6 py-2.5 rounded-lg transition-colors">
                {{ $testimonial ? 'Save changes' : 'Add testimonial' }}
            </button>
        </div>

    </form>
</div>
@endsection
