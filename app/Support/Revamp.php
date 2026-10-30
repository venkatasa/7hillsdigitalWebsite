<?php

namespace App\Support;

/**
 * Interim content for the revamp prototype, with the publication rule applied.
 *
 * Moves to the Offering and Industry models in Phase 2. The rule does not move:
 * an offering whose capability is unconfirmed is never published. On staging,
 * REVAMP_PREVIEW_UNCONFIRMED shows it — marked as a preview — so the owner can
 * judge the design; production ignores the switch entirely.
 */
class Revamp
{
    public const AVAILABILITY = [
        'available' => 'Available now',
        'built_to_order' => 'Built to your requirements',
        'in_development' => 'In development',
        'capability_unconfirmed' => 'Preview only — not published',
    ];

    /** Services that may be rendered, each with a `preview` flag. */
    public static function services(): array
    {
        $showUnconfirmed = config('revamp.preview_unconfirmed') && ! app()->isProduction();

        $out = [];

        foreach (config('revamp.services', []) as $slug => $service) {
            $unconfirmed = $service['availability'] === 'capability_unconfirmed';

            if ($unconfirmed && ! $showUnconfirmed) {
                continue;
            }

            $out[$slug] = $service + ['slug' => $slug, 'preview' => $unconfirmed];
        }

        return $out;
    }

    public static function industries(): array
    {
        return config('revamp.industries', []);
    }
}
