<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Mail and site storage stay SEPARATE, but become symmetric, and email
 * addresses become unlimited.
 *
 * The offer is now simple to say on a call: R880 buys 5GB of email and 5GB of
 * website storage; R1 180 buys 10GB of each. Keeping the two allowances apart
 * still means a dealer's photographs can never fill the sales inbox — the
 * reason they were split in the first place.
 *
 * `gb_per_mailbox` goes away and `mailboxes` becomes nullable, because the
 * count is no longer what is sold: null means unlimited addresses, all drawing
 * on the same mail allowance.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('package_tiers', function (Blueprint $table) {
            $table->dropColumn('gb_per_mailbox');
            $table->unsignedSmallInteger('mailboxes')->nullable()->default(null)->change();
        });

        // Site storage matches the mail allowance; addresses become unlimited.
        DB::table('package_tiers')->update([
            'media_gb' => DB::raw('mail_gb'),
            'mailboxes' => null,
        ]);
    }

    public function down(): void
    {
        Schema::table('package_tiers', function (Blueprint $table) {
            $table->unsignedSmallInteger('gb_per_mailbox')->default(1);
        });

        DB::table('package_tiers')->update([
            'mailboxes' => DB::raw('mail_gb'),
            'media_gb' => DB::raw('mail_gb * 2'),
        ]);

        Schema::table('package_tiers', function (Blueprint $table) {
            $table->unsignedSmallInteger('mailboxes')->nullable(false)->default(5)->change();
        });
    }
};
