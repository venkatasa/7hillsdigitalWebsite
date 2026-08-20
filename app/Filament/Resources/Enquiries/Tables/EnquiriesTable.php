<?php

namespace App\Filament\Resources\Enquiries\Tables;

use App\Models\Enquiry;
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
