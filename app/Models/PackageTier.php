<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A storage tier within a package line — "Showroom 5", "Showroom 10".
 *
 * Mail and site media are metered separately: pooling them lets a dealer's
 * photographs fill up the sales inbox.
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

    /** "5 mailboxes at 1GB each" */
    public function getMailboxSummaryAttribute(): string
    {
        return trans_choice(
            '{1}:count mailbox at :gb GB|[2,*]:count mailboxes at :gb GB each',
            $this->mailboxes,
            ['count' => $this->mailboxes, 'gb' => $this->gb_per_mailbox],
        );
    }
}
