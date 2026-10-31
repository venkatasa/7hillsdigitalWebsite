<?php

namespace App\Filament\Resources\Enquiries\Tables;

use App\Models\Enquiry;
use App\Support\Quote;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class EnquiriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('reference')
                    ->label('Ref')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->description(fn (Enquiry $r) => $r->created_at?->diffForHumans()),

                TextColumn::make('name')
                    ->label('Who')
                    ->searchable()
                    ->description(fn (Enquiry $r) => $r->company),

                TextColumn::make('interest')
                    ->label('Interested in')
                    ->badge()
                    ->color('gray'),

                // A badge and a sort order, never an automated reply (07_LEAD_GENERATION §7.4).
                TextColumn::make('priority')
                    ->label('Priority')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => Quote::priorityLabel($state))
                    ->color(fn (?string $state) => config("quote.priorities.{$state}.1", 'gray'))
                    ->placeholder('—')
                    ->sortable(query: fn ($query, string $direction) => $query->orderByRaw(
                        "CASE priority WHEN 'monthly' THEN 1 WHEN 'high' THEN 2 WHEN 'triage' THEN 3 WHEN 'standard' THEN 4 WHEN 'nurture' THEN 5 ELSE 6 END "
                        .($direction === 'desc' ? 'DESC' : 'ASC')
                    ))
                    ->visible(fn () => Quote::enabled()),

                TextColumn::make('email')
                    ->label('Contact')
                    ->searchable()
                    ->copyable()
                    ->description(fn (Enquiry $r) => $r->phone),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Enquiry::STATUSES[$state] ?? $state)
                    ->color(fn (string $state) => match ($state) {
                        'new' => 'warning',
                        'contacted', 'quoted' => 'info',
                        'won' => 'success',
                        'lost' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(Enquiry::STATUSES)
                    ->label('Status'),

                SelectFilter::make('journey')
                    ->label('Kind')
                    ->options(['subscription' => 'Platform subscription', 'project' => 'Project'])
                    ->visible(fn () => Quote::enabled()),

                SelectFilter::make('priority')
                    ->label('Priority')
                    ->options(collect(config('quote.priorities'))->map(fn ($p) => $p[0])->all())
                    ->visible(fn () => Quote::enabled()),

                SelectFilter::make('package')
                    ->relationship('package', 'name')
                    ->label('Package'),

                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make()->label('Open'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No enquiries yet')
            ->emptyStateDescription('Enquiries submitted on the website land here.');
    }
}
