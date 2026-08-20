<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('package_tiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();

            // "Showroom 5" — the line name plus the mail allowance in GB.
            $table->string('name');

            /*
             | Mail and media are metered separately on purpose. See the
             | 'storage' note in config/brand.php.
             */
            $table->unsignedSmallInteger('mail_gb');
            $table->unsignedSmallInteger('mailboxes');
            $table->unsignedSmallInteger('gb_per_mailbox')->default(1);
            $table->unsignedSmallInteger('media_gb');

            // ZAR per month, excl. VAT. Nullable so a tier can read "on request".
            $table->decimal('price_monthly', 10, 2)->nullable();

            $table->boolean('is_default')->default(false);
            $table->unsignedSmallInteger('sort')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_tiers');
    }
};
