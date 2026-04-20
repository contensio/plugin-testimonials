<?php

/**
 * Testimonials - Contensio plugin.
 * https://contensio.com
 *
 * @copyright   Copyright (c) 2026 Iosif Gabriel Chimilevschi
 * @license     https://www.gnu.org/licenses/agpl-3.0.txt  AGPL-3.0-or-later
 */

namespace Contensio\Testimonials\Models;

use Contensio\Models\PluginEntry;

/**
 * A testimonial stored in contensio_plugin_entries.
 *
 * Column mapping:
 *   title      → name
 *   content    → content (testimonial body)
 *   status     → pending/approved/rejected  (not the default active/inactive)
 *   data       → { role, company, avatar_url, rating, source, ip_address }
 */
class Testimonial extends PluginEntry
{
    const PLUGIN = 'contensio/plugin-testimonials';

    const STATUS_PENDING  = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    protected $attributes = [
        'plugin' => 'contensio/plugin-testimonials',
        'type'   => 'testimonial',
        'status' => 'pending',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('testimonials', fn ($q) => $q
            ->where('plugin', 'contensio/plugin-testimonials')
            ->where('type', 'testimonial')
        );
    }

    /* ── Column mapping ────────────────────────────────────────────────── */

    public function getNameAttribute(): ?string { return $this->title; }
    public function setNameAttribute(?string $v): void { $this->attributes['title'] = $v; }

    public function getRoleAttribute(): ?string { return $this->data['role'] ?? null; }
    public function setRoleAttribute(?string $v): void { $this->setDataField('role', $v); }

    public function getCompanyAttribute(): ?string { return $this->data['company'] ?? null; }
    public function setCompanyAttribute(?string $v): void { $this->setDataField('company', $v); }

    public function getAvatarUrlAttribute(): ?string { return $this->data['avatar_url'] ?? null; }
    public function setAvatarUrlAttribute(?string $v): void { $this->setDataField('avatar_url', $v); }

    public function getRatingAttribute(): ?int
    {
        $v = $this->data['rating'] ?? null;
        return $v !== null ? (int) $v : null;
    }
    public function setRatingAttribute(?int $v): void { $this->setDataField('rating', $v); }

    public function getSourceAttribute(): ?string { return $this->data['source'] ?? null; }
    public function setSourceAttribute(?string $v): void { $this->setDataField('source', $v); }

    public function getIpAddressAttribute(): ?string { return $this->data['ip_address'] ?? null; }
    public function setIpAddressAttribute(?string $v): void { $this->setDataField('ip_address', $v); }

    /* ── Scopes ────────────────────────────────────────────────────────── */

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /* ── Helpers ───────────────────────────────────────────────────────── */

    public function byline(): string
    {
        return implode(', ', array_filter([$this->role, $this->company]));
    }

    public function initials(): string
    {
        $parts = array_filter(explode(' ', trim($this->name ?? '')));
        if (count($parts) >= 2) {
            return strtoupper(mb_substr($parts[0], 0, 1) . mb_substr(end($parts), 0, 1));
        }
        return strtoupper(mb_substr($this->name ?? '', 0, 2));
    }

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
        return $colors[abs(crc32($this->name ?? '')) % count($colors)];
    }

    /* ── Private ───────────────────────────────────────────────────────── */

    private function setDataField(string $key, mixed $value): void
    {
        $data       = $this->data ?? [];
        $data[$key] = $value;
        $this->attributes['data'] = json_encode($data);
    }
}
