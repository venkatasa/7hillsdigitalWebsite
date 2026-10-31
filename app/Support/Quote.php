<?php

namespace App\Support;

use App\Models\Enquiry;

/**
 * The /quote form: which project types may be offered, what each one asks,
 * and how a submission is prioritised.
 *
 * A type is offered only where the service that proves it may render
 * (Revamp::visible), so a gated type is absent from the form AND rejected by
 * validation in production. "Something else" is always offered.
 */
class Quote
{
    /** True when /quote and lead attribution are switched on (needs the D7 migration). */
    public static function enabled(): bool
    {
        return (bool) config('revamp.leads');
    }

    /** Offerable types, keyed by slug: label, blurb, preview. "other" is always last. */
    public static function types(): array
    {
        $catalogue = Revamp::catalogue();
        $out = [];

        foreach (Revamp::quoteTypes() as $slug => [$label, $blurb, $gate]) {
            $out[$slug] = ['label' => $label, 'blurb' => $blurb, 'preview' => $catalogue[$gate]['preview'] ?? false];
        }

        [$label, $blurb] = config('quote.other');
        $out['other'] = ['label' => $label, 'blurb' => $blurb, 'preview' => false];

        return $out;
    }

    /** What a type asks: message [label, required], budget set, questions. */
    public static function type(string $slug): ?array
    {
        return config("quote.types.{$slug}");
    }

    /** Budget bands for a type, as [value => label]. */
    public static function budgets(string $type): array
    {
        $set = config('quote.budgets.'.(self::type($type)['budget'] ?? 'project'), []);

        return array_map(fn ($band) => $band[0], $set);
    }

    /**
     * Where a "Get a quote" link goes. With /quote switched off it falls back
     * to /contact exactly as before, so the switch can be flipped without
     * touching a template. `$type` may also be a service line slug.
     */
    public static function url(string $from, ?string $type = null): string
    {
        $type = config("quote.lines.{$type}", $type);

        if (! self::enabled()) {
            return route('contact', array_filter(['type' => 'project', 'interest' => $type, 'from' => $from]));
        }

        $type = array_key_exists((string) $type, self::types()) ? $type : null;

        return route('quote', array_filter(['type' => $type, 'from' => $from]));
    }

    /**
     * Priority from what was submitted (07_LEAD_GENERATION.md §7.4). A badge and
     * a sort order: it never replies to anyone or moves an enquiry on.
     */
    public static function priority(string $type, array $answers, ?string $budget, ?string $timeframe): string
    {
        if ($type === 'other') {
            return 'triage';
        }

        if (in_array($type, ['managed-it', 'microsoft-365'], true) && ($answers['users'] ?? '1-10') !== '1-10') {
            return 'monthly';
        }

        $set = self::type($type)['budget'] ?? 'project';
        if (config("quote.budgets.{$set}.{$budget}.1") === true || $timeframe === 'asap') {
            return 'high';
        }

        if ($timeframe === 'exploring' && $budget === 'not-sure') {
            return 'nurture';
        }

        return 'standard';
    }

    public static function typeLabel(?string $type): ?string
    {
        if ($type === null) {
            return null;
        }

        return $type === 'other'
            ? config('quote.other.0')
            : (config("revamp.quote_types.{$type}.0") ?? $type);
    }

    public static function priorityLabel(?string $priority): ?string
    {
        return $priority ? (config("quote.priorities.{$priority}.0") ?? $priority) : null;
    }

    /** The submitted answers as [question => answer] in plain words, for the panel and the mail. */
    public static function describe(Enquiry $enquiry): array
    {
        $out = [];
        $questions = self::type((string) $enquiry->project_type)['questions'] ?? [];

        foreach ($questions as $id => [$question, $options]) {
            if (isset($enquiry->answers[$id])) {
                $out[$question] = $options[$enquiry->answers[$id]] ?? $enquiry->answers[$id];
            }
        }

        $out['Budget'] = self::budgets((string) $enquiry->project_type)[$enquiry->budget_band] ?? $enquiry->budget_band;
        $out['Timeframe'] = config("quote.timeframes.{$enquiry->timeframe}") ?? $enquiry->timeframe;
        $out['Company size'] = config("quote.company_sizes.{$enquiry->company_size}") ?? $enquiry->company_size;
        $out['Prefers'] = config("quote.preferred_contact.{$enquiry->preferred_contact}") ?? $enquiry->preferred_contact;

        return array_filter($out, fn ($v) => $v !== null && $v !== '');
    }
}
