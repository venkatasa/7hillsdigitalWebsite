<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 | An append-only history of each enquiry: received, status changed, first
 | contacted. There is no updated_at because a row is never updated, and the
 | panel offers no way to edit or delete one. Staging only until approved
 | for production (D7).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enquiry_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enquiry_id')->constrained()->cascadeOnDelete();
            // Null for the system (the public form), otherwise the staff member.
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 30);
            $table->string('from', 60)->nullable();
            $table->string('to', 60)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['enquiry_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enquiry_events');
    }
};
