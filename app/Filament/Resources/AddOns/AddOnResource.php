<?php

namespace App\Filament\Resources\AddOns;

use App\Filament\Resources\AddOns\Pages\CreateAddOn;
use App\Filament\Resources\AddOns\Pages\EditAddOn;
use App\Filament\Resources\AddOns\Pages\ListAddOns;
use App\Filament\Resources\AddOns\Schemas\AddOnForm;
use App\Filament\Resources\AddOns\Tables\AddOnsTable;
use App\Models\AddOn;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AddOnResource extends Resource
{
    protected static ?string $model = AddOn::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPuzzlePiece;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Add-on modules';

    public static function form(Schema $schema): Schema
    {
        return AddOnForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AddOnsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAddOns::route('/'),
            'create' => CreateAddOn::route('/create'),
            'edit' => EditAddOn::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
