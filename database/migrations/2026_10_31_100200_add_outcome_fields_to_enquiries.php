<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 | How an enquiry ended (07_LEAD_GENERATION.md §7.7): why it was lost, and what a
 | win is worth, split once-off against monthly because new monthly revenue is the
 | headline KPI. Additive and nullable, under the same D7 approval as the project
 | fields: staging only until production deployment is approved.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->string('lost_reason', 30)->nullable()->after('status');
            $table->decimal('won_once_off', 10, 2)->nullable()->after('lost_reason');
            $table->decimal('won_monthly', 10, 2)->nullable()->after('won_once_off');
        });
    }

    public function down(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropColumn(['lost_reason', 'won_once_off', 'won_monthly']);
        });
    }
};
