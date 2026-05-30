<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add restriction fields to events table
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('is_restricted')->default(false)->after('requires_approval');
            $table->text('restriction_reason')->nullable()->after('is_restricted');
        });

        // Create copyright_reports table
        Schema::create('copyright_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('subject');
            $table->text('description');
            $table->string('evidence_url')->nullable();
            $table->string('status')->default('pending'); // pending, resolved, dismissed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('copyright_reports');

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['is_restricted', 'restriction_reason']);
        });
    }
};
