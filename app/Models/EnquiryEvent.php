<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LogicException;

/**
 * One line of an enquiry's history. Append-only: a row is written once and
 * never changed or removed, so the history stays evidence of who did what.
 */
class EnquiryEvent extends Model
{
    public const UPDATED_AT = null;

    public const TYPES = [
        'received' => 'Received',
        'status_changed' => 'Status changed',
        'contacted' => 'First contacted',
    ];

    protected $guarded = [];

    protected static function booted(): void
    {
        static::updating(fn () => throw new LogicException('Enquiry history is append-only.'));
        static::deleting(fn () => throw new LogicException('Enquiry history is append-only.'));
    }

    public function enquiry(): BelongsTo
    {
        return $this->belongsTo(Enquiry::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
