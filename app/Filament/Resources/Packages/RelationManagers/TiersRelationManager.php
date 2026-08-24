<?php

namespace App\Filament\Resources\Packages\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * The prices. This is where they get changed — never in the seeder, which only
 * sets what a fresh install starts with.
 *
 * A tier is website storage and nothing else. Do not add a mail allowance back
 * here: email is sold per mailbox as the Enterprise email module.
 */
class TiersRelationManager extends RelationManager
{
    protected static string $relationship = 'tiers';

    protected static ?string $title = 'Tiers and pricing';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')
                    ->label('Tier name')
                    ->helperText('Conventionally the line name plus the mail allowance: "Showroom 5".')
                    ->required()
                    ->maxLength(60),

                TextInput::make('price_monthly')
                    ->label('Price per month (excl. VAT)')
                    ->helperText('Leave blank to show "On request" on the site.')
                    ->numeric()
                    ->prefix('R')
                    ->minValue(0),

                TextInput::make('media_gb')
                    ->label('Website storage (GB)')
                    ->helperText('The only allowance a package carries — pages, photographs and documents. Email is NOT included in a package; it is sold per mailbox as the Enterprise email module.')
                    ->numeric()
                    ->required()
                    ->minValue(1),

                Toggle::make('is_default')
                    ->label('Show this tier on the package card')
                    ->helperText('The entry tier. Only one per package should carry this.'),

                TextInput::make('sort')
                    ->label('Order')
                    ->numeric()
                    ->default(0)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->columns([
                TextColumn::make('name')->label('Tier')->weight('bold'),
                TextColumn::make('formatted_price')->label('Per month'),
                TextColumn::make('media_gb')->label('Website storage')->suffix('GB'),
                IconColumn::make('is_default')->label('On the card')->boolean(),
            ])
            ->headerActions([CreateAction::make()->label('Add a tier')])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->modelLabel('tier');
    }
}
