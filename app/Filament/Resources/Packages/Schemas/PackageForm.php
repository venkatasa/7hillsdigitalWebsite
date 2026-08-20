<?php

namespace App\Filament\Resources\Packages\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('The package')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Line name')
                            ->helperText('Listings, Showroom, Careers, Stay, Business.')
                            ->required()
                            ->maxLength(60)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, $set, $context) => $context === 'create' ? $set('slug', Str::slug((string) $state)) : null),

                        TextInput::make('slug')
                            ->label('URL slug')
                            ->helperText('Appears in the address: /packages/showroom. Changing it breaks existing links.')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(60),

                        TextInput::make('vertical_label')
                            ->label('Who it is for')
                            ->helperText('Shown under the package name: "Car dealerships".')
                            ->required()
                            ->maxLength(80),

                        Select::make('icon_key')
                            ->label('Icon')
                            ->options([
                                'home' => 'House (property)',
                                'car' => 'Car (dealership)',
                                'briefcase' => 'Briefcase (recruitment)',
                                'bed' => 'Bed (hospitality)',
                                'globe' => 'Globe (general)',
                            ])
                            ->default('globe')
                            ->native(false)
                            ->required(),

                        TextInput::make('tagline')
                            ->label('Tagline')
                            ->helperText('One line, on the card and at the top of the package page.')
                            ->required()
                            ->maxLength(120)
                            ->columnSpanFull(),

                        Textarea::make('intro')
                            ->label('Introduction')
                            ->rows(4)
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('What you get')
                    ->description('The bullet list on the package page. Put the strongest point first — only the first three appear on the card.')
                    ->schema([
                        Repeater::make('what_you_get')
                            ->hiddenLabel()
                            ->simple(
                                TextInput::make('item')->required()->maxLength(200),
                            )
                            ->addActionLabel('Add a point')
                            ->reorderable()
                            ->defaultItems(1),
                    ]),

                Section::make('Built for')
                    ->description('The kinds of business this package suits.')
                    ->schema([
                        Repeater::make('built_for')
                            ->hiddenLabel()
                            ->simple(
                                TextInput::make('item')->required()->maxLength(120),
                            )
                            ->addActionLabel('Add a business type')
                            ->reorderable()
                            ->defaultItems(1),
                    ]),

                Section::make('Publishing')
                    ->columns(2)
                    ->schema([
                        TextInput::make('demo_url')
                            ->label('Demo site address')
                            ->helperText('Leave blank and the demos page says "being built" instead of showing a broken link.')
                            ->url()
                            ->maxLength(255),

                        TextInput::make('sort')
                            ->label('Order on the site')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        Toggle::make('is_published')
                            ->label('Published')
                            ->helperText('Unpublished packages disappear from the site entirely.')
                            ->default(true),

                        Toggle::make('is_featured')
                            ->label('Flag as "most asked for"')
                            ->default(false),
                    ]),
            ]);
    }
}
