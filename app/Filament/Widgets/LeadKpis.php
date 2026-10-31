<?php

namespace App\Filament\Widgets;

use App\Models\Enquiry;
use App\Models\EnquiryEvent;
use App\Support\Quote;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * The monthly lead figures from 07_LEAD_GENERATION.md §7.7. The headline is new
 * monthly revenue won, because the project work exists to create it.
 *
 * A win counts in the month it was recorded, read from the append-only history,
 * not the month the enquiry arrived. Shown only where /quote is on, because the
 * columns and the history table exist only there.
 */
class LeadKpis extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected ?string $heading = 'This month';

    public static function canView(): bool
    {
        return Quote::enabled();
    }

    protected function getStats(): array
    {
        $from = now()->startOfMonth();
        $to = now();

        $wonIds = EnquiryEvent::where('type', 'status_changed')->where('to', 'won')
            ->whereBetween('created_at', [$from, $to])->pluck('enquiry_id')->unique();
        $won = Enquiry::whereIn('id', $wonIds)->where('status', 'won')->get(['won_monthly', 'won_once_off']);

        $arrived = Enquiry::whereBetween('created_at', [$from, $to])
            ->get(['journey', 'project_type', 'priority', 'status', 'created_at', 'contacted_at', 'referrer_host', 'utm_source']);

        // Time to first contact: the median, so one forgotten lead does not hide the rest.
        $hours = $arrived->filter(fn ($e) => $e->contacted_at)
            ->map(fn ($e) => $e->created_at->diffInMinutes($e->contacted_at) / 60)->sort()->values();
        $median = $hours->isEmpty() ? null : $hours->median();

        // The funnel looks back 90 days, long enough for a proposal to be decided.
        $recent = Enquiry::where('created_at', '>=', now()->subDays(90))->pluck('status');
        $proposed = $recent->filter(fn ($s) => in_array($s, ['quoted', 'won'], true))->count();
        $signed = $recent->filter(fn ($s) => $s === 'won')->count();

        $projects = $arrived->where('journey', 'project');
        $topTypes = $projects->countBy('project_type')->sortDesc()->take(2)
            ->map(fn ($n, $t) => Quote::typeLabel($t)." {$n}")->implode(' · ');

        $source = $arrived->countBy(fn ($e) => $e->utm_source ?: ($e->referrer_host ?: 'direct'))->sortDesc();

        return [
            Stat::make('New monthly revenue won', 'R'.number_format((float) $won->sum('won_monthly'), 0, '.', ' '))
                ->description($won->count().' '.str('win')->plural($won->count()).' recorded this month')
                ->color('success'),

            Stat::make('Once-off work won', 'R'.number_format((float) $won->sum('won_once_off'), 0, '.', ' '))
                ->description('Excl. VAT'),

            // The overview above already counts every enquiry; this one is projects only.
            Stat::make('Project enquiries', $projects->count())
                ->description($topTypes ?: 'None yet this month'),

            Stat::make('High or monthly priority', $projects->isEmpty() ? '—'
                    : round($projects->whereIn('priority', ['high', 'monthly'])->count() / $projects->count() * 100).'%')
                ->description('Of project enquiries'),

            Stat::make('Time to first contact', $median === null ? '—' : self::duration($median))
                ->description('Median, enquiries this month')
                ->color($median !== null && $median > 24 ? 'warning' : 'gray'),

            Stat::make('Enquiry → proposal → won', $recent->isEmpty() ? '—'
                    : round($proposed / $recent->count() * 100).'% → '.round($signed / $recent->count() * 100).'%')
                ->description('Last 90 days. Top source: '.($source->keys()->first() ?? '—')),
        ];
    }

    private static function duration(float $hours): string
    {
        return $hours < 1 ? round($hours * 60).' min' : ($hours < 48 ? round($hours, 1).' h' : round($hours / 24, 1).' days');
    }
}
