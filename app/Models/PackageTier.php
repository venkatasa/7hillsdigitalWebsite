<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A storage tier within a package line — "Showroom 5", "Showroom 10".
 *
 * A package is the WEBSITE only. `media_gb` is the allowance for the site's own
 * content — photographs, documents, uploads — and the tier is named after it.
 * Email is not included at any tier; mailboxes are sold separately as the
 * Enterprise email module, priced per mailbox.
 */
class PackageTier extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'price_monthly' => 'decimal:2',
            'is_default' => 'boolean',
        ];
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /** "R1 495" — ZAR, no cents, thin space as the thousands separator. */
    public function getFormattedPriceAttribute(): string
    {
        if ($this->price_monthly === null) {
            return 'On request';
        }

        return 'R'.number_format((float) $this->price_monthly, 0, '.', ' ');
    }

    /** "5GB website storage" — the only allowance a package carries. */
    public function getStorageSummaryAttribute(): string
    {
        return $this->media_gb.'GB website storage';
    }

    /** "5GB" — the headline figure the tier is named after. */
    public function getHeadlineStorageAttribute(): string
    {
        return $this->media_gb.'GB';
    }
}
