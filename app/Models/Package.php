<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * A product line — Listings, Showroom, Careers, Stay or Business.
 *
 * The line is the thing we sell; the storage tiers underneath it are the
 * thing the client chooses between.
 */
class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'what_you_get' => 'array',
            'built_for' => 'array',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function tiers(): HasMany
    {
        return $this->hasMany(PackageTier::class)->orderBy('sort');
    }

    public function enquiries(): HasMany
    {
        return $this->hasMany(Enquiry::class);
    }

    /** The tier shown on the pricing card — the entry point, normally the 5GB. */
    public function defaultTier(): ?PackageTier
    {
        return $this->tiers->firstWhere('is_default', true) ?? $this->tiers->first();
    }

    /**
     * The demo tenant built for this line, if there is one. Demos live in
     * config/demos.php rather than the database — they are fixed marketing
     * assets, not something staff edit.
     */
    public function demoSlug(): ?string
    {
        foreach (config('demos', []) as $slug => $demo) {
            if (($demo['package'] ?? null) === $this->slug) {
                return $slug;
            }
        }

        return null;
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
