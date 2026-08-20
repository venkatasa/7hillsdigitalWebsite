<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();

            // The product line: Listings, Showroom, Careers, Stay, Business.
            $table->string('name');

            // Who it is for, in the client's own words: "Car dealerships".
            $table->string('vertical_label');

            $table->string('tagline');
            $table->text('intro');

            // Bullet lists rendered on the package page.
            $table->json('what_you_get')->nullable();
            $table->json('built_for')->nullable();

            // The seeded demo tenant for this package, once it exists.
            $table->string('demo_url')->nullable();

            // Key into the icon component; not a file path.
            $table->string('icon_key')->default('globe');

            $table->unsignedSmallInteger('sort')->default(0);
            $table->boolean('is_published')->default(true);
            $table->boolean('is_featured')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
