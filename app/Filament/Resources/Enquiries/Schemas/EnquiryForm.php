<?php

namespace App\Filament\Resources\Enquiries\Schemas;

use App\Models\Enquiry;
use App\Support\Quote;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class EnquiryForm
{
    public static function configure(Schema $schema): Schema
    {
        // Subscription-only fields are hidden on a project enquiry, and vice versa.
        $project = fn (?Enquiry $record) => $record?->journey === 'project';

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
                        TextInput::make('created_at')->label('Received')
                            ->formatStateUsing(fn ($state) => $state ? \Illuminate\Support\Carbon::parse($state)->format('d/m/Y H:i') : null),
                        TextInput::make('name')->label('Name'),
                        TextInput::make('company')->label('Business'),
                        TextInput::make('email')->label('Email'),
                        TextInput::make('phone')->label('Telephone'),
                        TextInput::make('package.name')->label('Package')->hidden($project),
                        TextInput::make('tier.name')->label('Tier')->hidden($project),
                        TextInput::make('industry')->label('Their industry')->hidden($project),
                        TextInput::make('current_website')->label('Current website')->hidden($project),
                        TextInput::make('role')->label('Role')->visible($project),
                        Textarea::make('message')->label('Message')->rows(4)->columnSpanFull(),
                    ])
                    ->disabled()
                    ->dehydrated(false),

                Section::make('The project')
                    ->description('Their answers on /quote. Priority is worked out from them when the enquiry arrives. Not editable.')
                    ->columns(2)
                    ->visible($project)
                    ->schema([
                        TextInput::make('project_type')->label('What they need')
                            ->formatStateUsing(fn (?string $state) => Quote::typeLabel($state)),
                        TextInput::make('priority')->label('Priority')
                            ->formatStateUsing(fn (?string $state) => Quote::priorityLabel($state)),
                        Textarea::make('answers')->label('Answers')
                            ->formatStateUsing(fn ($state, ?Enquiry $record) => $record
                                ? collect(Quote::describe($record))->map(fn ($a, $q) => "{$q} — {$a}")->implode("\n")
                                : null)
                            ->rows(7)
                            ->columnSpanFull(),
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
                            ->live()
                            ->native(false),
                        /*
                         | How it ended. These columns exist only where the D7
                         | migrations have run, which is exactly where /quote is
                         | switched on, so they are hidden (and never saved)
                         | anywhere else.
                         */
                        Select::make('lost_reason')
                            ->label('Why it was lost')
                            ->options(Enquiry::LOST_REASONS)
                            ->native(false)
                            ->visible(fn (Get $get) => Quote::enabled() && $get('status') === 'lost')
                            ->required(fn (Get $get) => $get('status') === 'lost'),
                        TextInput::make('won_once_off')
                            ->label('Won: once-off (R, excl. VAT)')
                            ->numeric()->minValue(0)->prefix('R')
                            ->visible(fn (Get $get) => Quote::enabled() && $get('status') === 'won'),
                        TextInput::make('won_monthly')
                            ->label('Won: monthly (R, excl. VAT)')
                            ->numeric()->minValue(0)->prefix('R')
                            ->helperText('New monthly revenue: the headline figure on the dashboard.')
                            ->visible(fn (Get $get) => Quote::enabled() && $get('status') === 'won'),
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

                Section::make('How they found us')
                    ->description('The first page of their visit, the site that sent them (domain only), any campaign tag, and the button they pressed.')
                    ->columns(3)
                    ->collapsed()
                    ->visible(fn () => Quote::enabled())
                    ->schema([
                        TextInput::make('landing_page')->label('First page'),
                        TextInput::make('referrer_host')->label('Referred by')->placeholder('Direct, or unknown'),
                        TextInput::make('cta_source')->label('Button'),
                        TextInput::make('utm_source')->label('Campaign source'),
                        TextInput::make('utm_medium')->label('Campaign medium'),
                        TextInput::make('utm_campaign')->label('Campaign'),
                    ])
                    ->disabled()
                    ->dehydrated(false),

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
