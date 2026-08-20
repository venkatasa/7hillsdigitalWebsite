<?php

namespace App\Filament\Widgets;

use App\Models\Enquiry;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EnquiryOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $unanswered = Enquiry::unanswered()->count();
        $thisMonth = Enquiry::whereBetween('created_at', [now()->startOfMonth(), now()])->count();
        $won = Enquiry::where('status', 'won')->count();
        $closed = Enquiry::whereIn('status', ['won', 'lost'])->count();

        return [
            Stat::make('Waiting for a reply', $unanswered)
                ->description($unanswered === 0 ? 'Nothing outstanding' : 'Enquiries nobody has answered yet')
                ->color($unanswered > 0 ? 'warning' : 'success'),

            Stat::make('Enquiries this month', $thisMonth)
                ->description(now()->format('F Y')),

            Stat::make('Signed up', $won)
                ->description($closed > 0
                    ? round($won / $closed * 100).'% of decided enquiries'
                    : 'No decisions recorded yet')
                ->color('success'),
        ];
    }
}
