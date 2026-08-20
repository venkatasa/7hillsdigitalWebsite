<?php

namespace App\Filament\Resources\Enquiries\Schemas;

use App\Models\Enquiry;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EnquiryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                /*
                 | What the prospect sent is READ-ONLY. disabled() plus
                 | dehydrated(false) means a save cannot rewrite what was
                 | actually submitted — the record stays evidence of what
                 | they told us, which is the point of keeping consent and
                 | provenance alongside it.
                 */
                Section::make('What they sent')
                    ->description('Submitted by the prospect. Not editable.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('reference')->label('Reference'),
                        TextInput::make('created_at')->label('Received'),
                        TextInput::make('name')->label('Name'),
                        TextInput::make('company')->label('Business'),
                        TextInput::make('email')->label('Email'),
                        TextInput::make('phone')->label('Telephone'),
                        TextInput::make('package.name')->label('Package'),
                        TextInput::make('tier.name')->label('Tier'),
                        TextInput::make('industry')->label('Their industry'),
                        TextInput::make('current_website')->label('Current website'),
                        Textarea::make('message')->label('Message')->rows(4)->columnSpanFull(),
                    ])
                    ->disabled()
                    ->dehydrated(false),

                Section::make('Our follow-up')
                    ->description('The only part of this record you can change.')
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->options(Enquiry::STATUSES)
                            ->default('new')
                            ->required()
                            ->native(false),
                        DateTimePicker::make('contacted_at')
                            ->label('First contacted')
                            ->seconds(false)
                            ->displayFormat('d/m/Y H:i'),
                        Textarea::make('internal_notes')
                            ->label('Internal notes')
                            ->rows(5)
                            ->columnSpanFull()
                            ->helperText('Visible to staff only. Never shown to the prospect.'),
                    ]),

                Section::make('POPIA record')
                    ->description('Captured at the point of collection. Kept as proof of consent.')
                    ->columns(3)
                    ->collapsed()
                    ->schema([
                        TextInput::make('consent')->label('Consent given')
                            ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No'),
                        TextInput::make('ip_address')->label('IP address'),
                        TextInput::make('user_agent')->label('Browser'),
                    ])
                    ->disabled()
                    ->dehydrated(false),
            ]);
    }
}
