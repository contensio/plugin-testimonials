<?php

/**
 * Testimonials — Contensio plugin.
 * https://contensio.com
 *
 * @copyright   Copyright (c) 2026 Iosif Gabriel Chimilevschi
 * @license     https://www.gnu.org/licenses/agpl-3.0.txt  AGPL-3.0-or-later
 */

namespace Contensio\Testimonials\Http\Controllers\Frontend;

use Contensio\Testimonials\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SubmitController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:150',
            'role'    => 'nullable|string|max:150',
            'company' => 'nullable|string|max:150',
            'content' => 'required|string|min:10|max:2000',
            'rating'  => 'nullable|integer|min:1|max:5',
        ]);

        Testimonial::create([
            'name'       => strip_tags(trim($request->input('name'))),
            'role'       => $request->filled('role')    ? strip_tags(trim($request->input('role')))    : null,
            'company'    => $request->filled('company') ? strip_tags(trim($request->input('company'))) : null,
            'content'    => strip_tags(trim($request->input('content'))),
            'rating'     => $request->filled('rating') ? (int) $request->input('rating') : null,
            'status'     => Testimonial::STATUS_PENDING,
            'source'     => 'form',
            'ip_address' => $request->ip(),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('testimonial_submitted', true);
    }
}
