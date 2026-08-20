<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmptyStateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A fresh install with no packages seeded must still serve the site.
     * The pages that iterate packages should render empty, not fall over.
     */
    public function test_the_site_stands_up_with_no_packages(): void
    {
        foreach (['/', '/packages', '/demos', '/contact'] as $path) {
            $this->get($path)->assertOk();
        }
    }
}
