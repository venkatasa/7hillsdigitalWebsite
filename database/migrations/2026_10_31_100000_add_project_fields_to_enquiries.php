<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 | Project enquiries and lead attribution (decision D7, approved 31/10/2026 for
 | STAGING ONLY). Every column is additive and nullable, or defaulted, so no
 | existing row or column changes meaning. Running this on production is a
 | deployment decision that needs its own approval.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            // Every enquiry before /quote was about a subscription package.
            $table->string('journey', 20)->default('subscription')->after('reference');

            // What the prospect told us on /quote. Read-only in the panel.
            $table->string('project_type', 40)->nullable()->after('package_tier_id');
            $table->json('answers')->nullable()->after('project_type');
            $table->string('budget_band', 30)->nullable()->after('answers');
            $table->string('timeframe', 30)->nullable()->after('budget_band');
            $table->string('company_size', 30)->nullable()->after('timeframe');
            $table->string('role', 80)->nullable()->after('company_size');
            $table->string('preferred_contact', 20)->nullable()->after('role');

            // Derived from the submission when it is saved: a badge and a sort
            // order, never an automated reply.
            $table->string('priority', 20)->nullable()->after('preferred_contact');

            // How they arrived. Referring DOMAIN only, never the full URL.
            $table->string('landing_page', 255)->nullable()->after('user_agent');
            $table->string('referrer_host', 100)->nullable()->after('landing_page');
            $table->string('utm_source', 100)->nullable()->after('referrer_host');
            $table->string('utm_medium', 100)->nullable()->after('utm_source');
            $table->string('utm_campaign', 100)->nullable()->after('utm_medium');
            $table->string('cta_source', 60)->nullable()->after('utm_campaign');

            $table->index(['journey', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropIndex(['journey', 'created_at']);
            $table->dropColumn([
                'journey', 'project_type', 'answers', 'budget_band', 'timeframe',
                'company_size', 'role', 'preferred_contact', 'priority',
                'landing_page', 'referrer_host', 'utm_source', 'utm_medium',
                'utm_campaign', 'cta_source',
            ]);
        });
    }
};
