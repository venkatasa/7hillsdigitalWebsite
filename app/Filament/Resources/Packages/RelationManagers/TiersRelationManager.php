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
 * The prices. This is where they get changed — never in the seeder, which
 * only sets the placeholders a fresh install starts with.
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

                TextInput::make('mailboxes')
                    ->label('Mailboxes included')
                    ->numeric()
                    ->required()
                    ->minValue(1),

                TextInput::make('gb_per_mailbox')
                    ->label('GB per mailbox')
                    ->numeric()
                    ->required()
                    ->default(1)
                    ->minValue(1),

                TextInput::make('mail_gb')
                    ->label('Total mail storage (GB)')
                    ->numeric()
                    ->required()
                    ->minValue(1),

                TextInput::make('media_gb')
                    ->label('Website storage (GB)')
                    ->helperText('Separate from mail, so photographs can never fill the inbox.')
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
                TextColumn::make('mailboxes')->label('Mailboxes'),
                TextColumn::make('mail_gb')->label('Mail')->suffix('GB'),
                TextColumn::make('media_gb')->label('Site')->suffix('GB'),
                IconColumn::make('is_default')->label('On the card')->boolean(),
            ])
            ->headerActions([CreateAction::make()->label('Add a tier')])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->modelLabel('tier');
    }
}
