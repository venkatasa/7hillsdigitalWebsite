<?php

namespace App\Filament\Resources\Enquiries\Pages;

use App\Filament\Resources\Enquiries\EnquiryResource;
use App\Models\Enquiry;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListEnquiries extends ListRecords
{
    protected static string $resource = EnquiryResource::class;

    /** Enquiries arrive from the public form; there is nothing to create here. */
    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        $tabs = [
            'all' => Tab::make('All'),
        ];

        foreach (Enquiry::STATUSES as $value => $label) {
            $tabs[$value] = Tab::make($label)
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', $value))
                ->badge(Enquiry::where('status', $value)->count());
        }

        return $tabs;
    }
}
