<?php

namespace App\Filament\Resources\AddOns\Tables;

use App\Models\AddOn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AddOnsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->reorderable('sort')
            ->columns([
                TextColumn::make('name')
                    ->label('Module')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn (AddOn $r) => $r->tagline, position: 'below'),

                TextColumn::make('price')
                    ->label('Price')
                    ->state(fn (AddOn $r) => $r->is_metered ? 'On request' : '+'.$r->formatted_price.' / month')
                    ->description(fn (AddOn $r) => $r->is_metered ? $r->price_basis : null),

                IconColumn::make('is_published')
                    ->label('Live')
                    ->boolean(),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ])
            ->emptyStateHeading('No modules yet');
    }
}
