<?php

namespace App\Filament\Resources\Enquiries\RelationManagers;

use App\Models\Enquiry;
use App\Models\EnquiryEvent;
use App\Support\Quote;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * The enquiry's history, read-only: no create, edit or delete action exists,
 * and the model refuses an update or delete even if one were added.
 */
class EventsRelationManager extends RelationManager
{
    protected static string $relationship = 'events';

    protected static ?string $title = 'History';

    // A handful of rows: load them with the page rather than after it.
    protected static bool $isLazy = false;

    public function isReadOnly(): bool
    {
        return true;
    }

    /** The table behind it exists only where the D7 migration has run. */
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return Quote::enabled();
    }

    public function table(Table $table): Table
    {
        $status = fn (?string $s) => $s ? (Enquiry::STATUSES[$s] ?? $s) : null;

        return $table
            ->defaultSort('id', 'desc')
            ->paginated(false)
            ->columns([
                TextColumn::make('created_at')->label('When')->dateTime('d/m/Y H:i'),
                TextColumn::make('type')->label('What')
                    ->formatStateUsing(fn (string $state) => EnquiryEvent::TYPES[$state] ?? $state),
                TextColumn::make('change')->label('Change')
                    ->state(fn (EnquiryEvent $r) => $r->type === 'status_changed'
                        ? $status($r->from).' → '.$status($r->to)
                        : null)
                    ->placeholder('—'),
                TextColumn::make('user.name')->label('By')->placeholder('The website'),
            ]);
    }
}
