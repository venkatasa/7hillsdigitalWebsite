<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();

            // Generated, never typed: ENQ-2026-00001. Zero-padded so
            // LENGTH() DESC, reference DESC sorts numerically on any database.
            $table->string('reference')->unique();

            $table->string('name');
            $table->string('company')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();

            // What they enquired about. Both nullable: a prospect can come in
            // off the contact page without picking anything.
            $table->foreignId('package_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('package_tier_id')->nullable()->constrained()->nullOnDelete();

            // Free text for "my industry isn't listed" — this is the signal
            // that tells us which vertical to build next.
            $table->string('industry')->nullable();

            $table->string('current_website')->nullable();
            $table->text('message')->nullable();

            // POPIA: explicit consent, recorded with provenance.
            $table->boolean('consent')->default(false);
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent', 512)->nullable();

            $table->string('status')->default('new');
            $table->timestamp('contacted_at')->nullable();
            $table->foreignId('contacted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('internal_notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};
