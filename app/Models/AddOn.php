<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * An optional business module bolted onto a package — invoicing, CRM, ERP.
 *
 * These are charged SEPARATELY from the monthly package fee. They are not
 * included in any tier, and the packages page says so plainly: a client who
 * discovers a surprise line on their first invoice is a client who cancels,
 * and cancelling costs them nothing.
 */
class AddOn extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'add_ons';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'what_it_does' => 'array',
            'price_monthly' => 'decimal:2',
            'is_published' => 'boolean',
        ];
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** "R109" for a flat module, or the basis line for a metered one. */
    public function getFormattedPriceAttribute(): string
    {
        if ($this->price_monthly === null) {
            return 'On request';
        }

        return 'R'.number_format((float) $this->price_monthly, 0, '.', ' ');
    }

    public function getIsMeteredAttribute(): bool
    {
        return $this->price_monthly === null;
    }
}
