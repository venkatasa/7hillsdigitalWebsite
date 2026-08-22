<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A storage tier within a package line — "Showroom 5", "Showroom 10".
 *
 * Mail and site storage are metered SEPARATELY and symmetrically: the 5 tier
 * is 5GB of each, the 10 tier is 10GB of each. Keeping them apart is what
 * stops a dealer's photographs filling up the sales inbox.
 *
 * `mailboxes` null means unlimited addresses, which is what every tier uses —
 * the count is not what is sold, the mail allowance is.
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

    /** "Unlimited email addresses", or a count if one is ever set. */
    public function getMailboxSummaryAttribute(): string
    {
        if ($this->mailboxes === null) {
            return 'Unlimited email addresses';
        }

        return trans_choice(
            '{1}:count mailbox|[2,*]:count mailboxes',
            $this->mailboxes,
            ['count' => $this->mailboxes],
        );
    }

    /** "5GB email + 5GB website" — the two allowances, said in full. */
    public function getStorageSummaryAttribute(): string
    {
        return "{$this->mail_gb}GB email + {$this->media_gb}GB website";
    }

    /** "5GB" — the headline figure the tier is named after. */
    public function getHeadlineStorageAttribute(): string
    {
        return $this->mail_gb.'GB';
    }
}
