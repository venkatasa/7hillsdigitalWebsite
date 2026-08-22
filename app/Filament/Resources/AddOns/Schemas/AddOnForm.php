<?php

namespace App\Filament\Resources\AddOns\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class AddOnForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('The module')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->maxLength(60)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, $set, $context) => $context === 'create' ? $set('slug', Str::slug((string) $state)) : null),

                        TextInput::make('slug')
                            ->label('URL slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(60),

                        Select::make('icon_key')
                            ->label('Icon')
                            ->options([
                                'invoice' => 'Invoice',
                                'people' => 'People (CRM)',
                                'boxes' => 'Boxes (ERP / stock)',
                                'module' => 'Generic module',
                            ])
                            ->default('module')
                            ->native(false)
                            ->required(),

                        TextInput::make('sort')
                            ->label('Order on the site')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        TextInput::make('tagline')
                            ->label('Tagline')
                            ->helperText('One line, shown on the card.')
                            ->required()
                            ->maxLength(140)
                            ->columnSpanFull(),

                        Textarea::make('intro')
                            ->label('Introduction')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('Pricing')
                    ->description('Charged on top of the monthly package fee — never bundled into a tier.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('price_monthly')
                            ->label('Price per month (excl. VAT)')
                            ->helperText('Leave BLANK for a module quoted on transaction volume, then fill in the basis alongside.')
                            ->numeric()
                            ->prefix('R')
                            ->minValue(0)
                            ->live(onBlur: true),

                        TextInput::make('price_basis')
                            ->label('Quoting basis')
                            ->helperText('Shown instead of a price when the price is blank. E.g. "Quoted on your transaction volume".')
                            ->maxLength(160),
                    ]),

                Section::make('What it does')
                    ->description('Only the first four appear on the card.')
                    ->schema([
                        Repeater::make('what_it_does')
                            ->hiddenLabel()
                            ->simple(TextInput::make('item')->required()->maxLength(200))
                            ->addActionLabel('Add a point')
                            ->reorderable()
                            ->defaultItems(1),
                    ]),

                Section::make('Publishing')
                    ->schema([
                        Toggle::make('is_published')
                            ->label('Published')
                            ->helperText('Unpublished modules disappear from the site entirely.')
                            ->default(true),
                    ]),
            ]);
    }
}
