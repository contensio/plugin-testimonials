@extends('contensio::admin.layout')

@section('title', 'Testimonials')

@section('content')
<div class="p-6">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Testimonials</h1>
            <p class="mt-1 text-gray-500">Review and manage submitted testimonials.</p>
        </div>
        <a href="{{ route('testimonials.create') }}"
           class="inline-flex items-center gap-2 bg-ember-500 hover:bg-ember-600 text-white font-semibold text-sm px-4 py-2.5 rounded-lg transition-colors">
            <i class="bi bi-plus-lg"></i>
            Add testimonial
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-green-800 text-sm">
        {{ session('success') }}
    </div>
    @endif

    {{-- Status tabs --}}
    <div class="flex items-center gap-1 mb-5 border-b border-gray-200">
        @foreach(['all' => 'All', 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $tab => $label)
        <a href="{{ route('testimonials.index', ['status' => $tab]) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium border-b-2 transition-colors
                  {{ $status === $tab
                     ? 'border-ember-500 text-ember-600'
                     : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            {{ $label }}
            <span class="text-xs {{ $status === $tab ? 'bg-ember-100 text-ember-700' : 'bg-gray-100 text-gray-500' }} rounded-full px-2 py-0.5 font-semibold tabular-nums">
                {{ $counts[$tab] }}
            </span>
        </a>
        @endforeach
    </div>

    @if($testimonials->isEmpty())
    <div class="bg-white border border-gray-200 rounded-xl py-16 text-center text-gray-400">
        <i class="bi bi-chat-quote text-4xl mb-3 block"></i>
        <p class="text-lg font-medium text-gray-500">No testimonials{{ $status !== 'all' ? ' with this status' : '' }}</p>
        @if($status === 'all')
        <p class="text-sm mt-1 mb-5">Add one manually or embed the submission form in your theme.</p>
        <a href="{{ route('testimonials.create') }}"
           class="inline-flex items-center gap-2 bg-ember-500 hover:bg-ember-600 text-white font-semibold text-sm px-4 py-2 rounded-lg transition-colors">
            <i class="bi bi-plus-lg"></i> Add a testimonial
        </a>
        @endif
    </div>
    @else
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wider text-gray-500">Person</th>
                    <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wider text-gray-500">Testimonial</th>
                    <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wider text-gray-500">Rating</th>
                    <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wider text-gray-500">Status</th>
                    <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-wider text-gray-500">Submitted</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($testimonials as $t)
                @php
                    $badgeClass = match($t->status) {
                        'approved' => 'bg-green-50 text-green-700 border border-green-200',
                        'rejected' => 'bg-red-50 text-red-700 border border-red-200',
                        default    => 'bg-amber-50 text-amber-700 border border-amber-200',
                    };
                @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-4 min-w-[160px]">
                        <p class="font-semibold text-gray-900">{{ $t->name }}</p>
                        @if($t->byline())
                        <p class="text-xs text-gray-400 mt-0.5">{{ $t->byline() }}</p>
                        @endif
                    </td>
                    <td class="px-5 py-4 max-w-xs">
                        <p class="text-gray-700 line-clamp-2 leading-snug">{{ $t->content }}</p>
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap">
                        @if($t->rating)
                        <span class="flex items-center gap-0.5">
                            @for($s = 1; $s <= 5; $s++)
                            <i class="bi {{ $s <= $t->rating ? 'bi-star-fill text-amber-400' : 'bi-star text-gray-200' }} text-sm leading-none"></i>
                            @endfor
                        </span>
                        @else
                        <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <span class="inline-block text-xs font-semibold px-2.5 py-1 rounded-full {{ $badgeClass }}">
                            {{ ucfirst($t->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-gray-400 text-xs whitespace-nowrap">
                        {{ $t->created_at->format('M j, Y') }}
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-end gap-1">
                            @if($t->status !== 'approved')
                            <form method="POST" action="{{ route('testimonials.approve', $t->id) }}">
                                @csrf
                                <button type="submit" title="Approve"
                                        class="text-gray-400 hover:text-green-600 transition-colors p-1">
                                    <i class="bi bi-check-lg text-base"></i>
                                </button>
                            </form>
                            @endif
                            @if($t->status !== 'rejected')
                            <form method="POST" action="{{ route('testimonials.reject', $t->id) }}">
                                @csrf
                                <button type="submit" title="Reject"
                                        class="text-gray-400 hover:text-red-500 transition-colors p-1">
                                    <i class="bi bi-x-lg text-base"></i>
                                </button>
                            </form>
                            @endif
                            <a href="{{ route('testimonials.edit', $t->id) }}"
                               class="text-gray-400 hover:text-ember-600 transition-colors p-1" title="Edit">
                                <i class="bi bi-pencil text-base"></i>
                            </a>
                            <form method="POST" action="{{ route('testimonials.destroy', $t->id) }}"
                                  onsubmit="return confirm('Delete this testimonial permanently?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="text-gray-400 hover:text-red-600 transition-colors p-1" title="Delete">
                                    <i class="bi bi-trash text-base"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($testimonials->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">{{ $testimonials->links() }}</div>
        @endif
    </div>
    @endif

    {{-- Embed reference --}}
    <div class="mt-6 bg-gray-50 border border-gray-200 rounded-xl p-5 space-y-3">
        <p class="text-sm font-semibold text-gray-700">Embed snippets</p>
        <div class="space-y-2">
            <div>
                <p class="text-xs text-gray-500 mb-1">Submission form</p>
                <code class="block text-xs font-mono bg-white border border-gray-200 rounded-lg px-3 py-2 text-gray-600 select-all">@{{ include('testimonials::partials.submit-form') }}</code>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Grid display</p>
                <code class="block text-xs font-mono bg-white border border-gray-200 rounded-lg px-3 py-2 text-gray-600 select-all">@{{ include('testimonials::partials.testimonials-grid') }}</code>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Carousel display</p>
                <code class="block text-xs font-mono bg-white border border-gray-200 rounded-lg px-3 py-2 text-gray-600 select-all">@{{ include('testimonials::partials.testimonials-carousel') }}</code>
            </div>
        </div>
    </div>

</div>
@endsection
