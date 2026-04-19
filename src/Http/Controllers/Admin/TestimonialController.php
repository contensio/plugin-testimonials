<?php

/**
 * Testimonials — Contensio plugin.
 * https://contensio.com
 *
 * @copyright   Copyright (c) 2026 Iosif Gabriel Chimilevschi
 * @license     https://www.gnu.org/licenses/agpl-3.0.txt  AGPL-3.0-or-later
 */

namespace Contensio\Testimonials\Http\Controllers\Admin;

use Contensio\Testimonials\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');

        $testimonials = Testimonial::query()
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(30)
            ->withQueryString();

        $counts = [
            'all'      => Testimonial::count(),
            'pending'  => Testimonial::pending()->count(),
            'approved' => Testimonial::approved()->count(),
            'rejected' => Testimonial::where('status', Testimonial::STATUS_REJECTED)->count(),
        ];

        return view('testimonials::admin.index', compact('testimonials', 'status', 'counts'));
    }

    public function create()
    {
        return view('testimonials::admin.form', ['testimonial' => null]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['source'] = 'manual';

        Testimonial::create($data);

        return redirect()->route('testimonials.index')->with('success', 'Testimonial added.');
    }

    public function edit(int $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        return view('testimonials::admin.form', compact('testimonial'));
    }

    public function update(Request $request, int $id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->update($this->validated($request));

        return redirect()->route('testimonials.index')->with('success', 'Testimonial updated.');
    }

    public function approve(int $id)
    {
        Testimonial::findOrFail($id)->update(['status' => Testimonial::STATUS_APPROVED]);

        return back()->with('success', 'Testimonial approved.');
    }

    public function reject(int $id)
    {
        Testimonial::findOrFail($id)->update(['status' => Testimonial::STATUS_REJECTED]);

        return back()->with('success', 'Testimonial rejected.');
    }

    public function destroy(int $id)
    {
        Testimonial::findOrFail($id)->delete();

        return back()->with('success', 'Testimonial deleted.');
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private function validated(Request $request): array
    {
        $request->validate([
            'name'       => 'required|string|max:150',
            'role'       => 'nullable|string|max:150',
            'company'    => 'nullable|string|max:150',
            'avatar_url' => 'nullable|url|max:500',
            'content'    => 'required|string|max:2000',
            'rating'     => 'nullable|integer|min:1|max:5',
            'status'     => 'required|in:pending,approved,rejected',
        ]);

        return [
            'name'       => strip_tags(trim($request->input('name'))),
            'role'       => $request->filled('role')    ? strip_tags(trim($request->input('role')))    : null,
            'company'    => $request->filled('company') ? strip_tags(trim($request->input('company'))) : null,
            'avatar_url' => $request->filled('avatar_url') ? trim($request->input('avatar_url')) : null,
            'content'    => strip_tags(trim($request->input('content'))),
            'rating'     => $request->filled('rating') ? (int) $request->input('rating') : null,
            'status'     => $request->input('status'),
        ];
    }
}
