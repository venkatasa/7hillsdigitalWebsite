<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('add_ons', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('tagline');
            $table->text('intro')->nullable();

            // What the module actually does, rendered as a bullet list.
            $table->json('what_it_does')->nullable();

            /*
             | Nullable on purpose. A module we can price flat (Invoicing) shows
             | a rand figure; one that depends on how much a client puts through
             | it (CRM, ERP) shows `price_basis` instead. Quoting a fixed number
             | for those would mean either overcharging a small firm or losing
             | money on a large one.
             */
            $table->decimal('price_monthly', 10, 2)->nullable();
            $table->string('price_basis')->nullable();

            $table->string('icon_key')->default('module');
            $table->unsignedSmallInteger('sort')->default(0);
            $table->boolean('is_published')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('add_ons');
    }
};
