<?php

/**
 * Testimonials — Contensio plugin.
 * https://contensio.com
 *
 * @copyright   Copyright (c) 2026 Iosif Gabriel Chimilevschi
 * @license     https://www.gnu.org/licenses/agpl-3.0.txt  AGPL-3.0-or-later
 */

namespace Contensio\Testimonials\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $table = 'testimonials';

    protected $fillable = [
        'name',
        'role',
        'company',
        'avatar_url',
        'content',
        'rating',
        'status',
        'source',
        'ip_address',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    const STATUS_PENDING  = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Display name combining role and company, e.g. "CEO, Acme Corp".
     */
    public function byline(): string
    {
        return implode(', ', array_filter([$this->role, $this->company]));
    }

    /**
     * Initials for the avatar fallback (up to 2 characters).
     */
    public function initials(): string
    {
        $parts = array_filter(explode(' ', trim($this->name)));
        if (count($parts) >= 2) {
            return strtoupper(mb_substr($parts[0], 0, 1) . mb_substr(end($parts), 0, 1));
        }
        return strtoupper(mb_substr($this->name, 0, 2));
    }

    /**
     * Deterministic avatar background colour based on the name.
     * Returns a Tailwind background + text class pair.
     */
    public function avatarColor(): string
    {
        $colors = [
            'bg-ember-100 text-ember-700',
            'bg-blue-100 text-blue-700',
            'bg-green-100 text-green-700',
            'bg-purple-100 text-purple-700',
            'bg-pink-100 text-pink-700',
            'bg-yellow-100 text-yellow-700',
            'bg-indigo-100 text-indigo-700',
            'bg-teal-100 text-teal-700',
        ];
        $index = abs(crc32($this->name)) % count($colors);
        return $colors[$index];
    }
}
