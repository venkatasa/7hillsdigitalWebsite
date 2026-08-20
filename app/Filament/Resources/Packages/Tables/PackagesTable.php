<?php

namespace App\Filament\Resources\Packages\Tables;

use App\Models\Package;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PackagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->reorderable('sort')
            ->columns([
                TextColumn::make('name')
                    ->label('Package')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn (Package $r) => $r->vertical_label),

                TextColumn::make('tiers.name')
                    ->label('Tiers')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('entry_price')
                    ->label('From')
                    ->state(fn (Package $r) => $r->defaultTier()?->formatted_price ?? '—')
                    ->description('per month'),

                IconColumn::make('is_published')
                    ->label('Live')
                    ->boolean(),

                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),

                TextColumn::make('enquiries_count')
                    ->label('Enquiries')
                    ->counts('enquiries')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
