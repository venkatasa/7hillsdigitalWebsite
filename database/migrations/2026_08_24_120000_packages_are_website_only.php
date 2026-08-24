<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Email comes out of the packages entirely.
 *
 * A package is now the website and nothing else: build, hosting, SSL, backups,
 * support, and a storage allowance for the site's own content. Mailboxes are
 * sold separately as the Enterprise email module, priced per mailbox.
 *
 * `media_gb` stays and is the website allowance the tier is named after.
 * `mail_gb` and `mailboxes` go, because a package no longer carries either and
 * leaving them would invite someone to quote them on a sales call.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('package_tiers', function (Blueprint $table) {
            $table->dropColumn(['mail_gb', 'mailboxes']);
        });

        Schema::table('add_ons', function (Blueprint $table) {
            /*
             | Some modules are not one number. Enterprise email is R20 for a
             | 5GB mailbox and R30 for a 10GB one, so the price is a small
             | table: [{label, price}]. `price_monthly` stays as the "from"
             | figure that the card leads with.
             */
            $table->json('price_tiers')->nullable()->after('price_basis');

            // "per mailbox, per month" — the unit the tiers above are charged in.
            $table->string('price_unit')->nullable()->after('price_tiers');
        });
    }

    public function down(): void
    {
        Schema::table('package_tiers', function (Blueprint $table) {
            $table->unsignedSmallInteger('mail_gb')->default(5);
            $table->unsignedSmallInteger('mailboxes')->nullable();
        });

        Schema::table('add_ons', function (Blueprint $table) {
            $table->dropColumn(['price_tiers', 'price_unit']);
        });
    }
};
