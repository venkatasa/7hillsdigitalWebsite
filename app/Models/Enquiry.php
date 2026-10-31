<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Support\Quote;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * A prospect asking about a package (journey `subscription`, from /contact) or
 * about a project (journey `project`, from /quote).
 *
 * Enquiries arrive as 'new' and are worked by hand — nothing here auto-
 * advances. POPIA provenance (consent, IP, user agent) is captured at the
 * point of collection and never rewritten afterwards.
 */
class Enquiry extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUSES = [
        'new' => 'New',
        'contacted' => 'Contacted',
        'quoted' => 'Quoted',
        'won' => 'Signed up',
        'lost' => 'Not proceeding',
    ];

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'consent' => 'boolean',
            'contacted_at' => 'datetime',
            'answers' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $enquiry) {
            $enquiry->reference ??= static::nextReference();
        });

        /*
         | The history table exists only where the D7 migration has run, which is
         | exactly where /quote is switched on.
         */
        static::created(function (self $enquiry) {
            if (Quote::enabled()) {
                $enquiry->events()->create(['type' => 'received', 'to' => $enquiry->status]);
            }
        });

        static::updated(function (self $enquiry) {
            if (! Quote::enabled()) {
                return;
            }

            if ($enquiry->wasChanged('status')) {
                $enquiry->events()->create([
                    'type' => 'status_changed',
                    'user_id' => auth()->id(),
                    'from' => $enquiry->getOriginal('status'),
                    'to' => $enquiry->status,
                ]);
            }

            if ($enquiry->wasChanged('contacted_at') && $enquiry->getOriginal('contacted_at') === null) {
                $enquiry->events()->create(['type' => 'contacted', 'user_id' => auth()->id()]);
            }
        });
    }

    /**
     * ENQ-2026-00001, zero-padded to five digits.
     *
     * Ordering by LENGTH() first means the sort stays numeric even if the
     * padding is ever outgrown.
     */
    public static function nextReference(): string
    {
        $year = now()->year;
        $prefix = "ENQ-{$year}-";

        return DB::transaction(function () use ($prefix) {
            $last = static::withTrashed()
                ->where('reference', 'like', $prefix.'%')
                ->lockForUpdate()
                ->orderByRaw('LENGTH(reference) DESC, reference DESC')
                ->value('reference');

            $next = $last ? ((int) substr($last, strlen($prefix))) + 1 : 1;

            return $prefix.str_pad((string) $next, 5, '0', STR_PAD_LEFT);
        });
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function tier(): BelongsTo
    {
        return $this->belongsTo(PackageTier::class, 'package_tier_id');
    }

    public function contactedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'contacted_by');
    }

    public function events(): HasMany
    {
        return $this->hasMany(EnquiryEvent::class)->latest('id');
    }

    public function scopeUnanswered($query)
    {
        return $query->where('status', 'new');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst((string) $this->status);
    }

    /** What they asked about, for a list column: project type, package name or free-text industry. */
    public function getInterestAttribute(): string
    {
        return Quote::typeLabel($this->project_type)
            ?? $this->tier?->name
            ?? $this->package?->name
            ?? $this->industry
            ?? 'General enquiry';
    }
}
