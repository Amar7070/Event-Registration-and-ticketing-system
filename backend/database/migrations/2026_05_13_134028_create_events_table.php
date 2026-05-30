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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('event_categories');
            $table->foreignId('venue_id')->nullable()->constrained('venues');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('short_description');
            $table->longText('description');
            $table->string('banner_image')->nullable();
            $table->string('thumbnail_image')->nullable();
            $table->enum('type', ['physical', 'online', 'hybrid'])->default('physical');
            $table->string('online_link')->nullable();
            $table->enum('status', ['draft', 'pending_approval', 'published', 'cancelled', 'completed'])->default('draft');
            $table->enum('visibility', ['public', 'private', 'unlisted'])->default('public');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->dateTime('registration_start')->nullable();
            $table->dateTime('registration_end')->nullable();
            $table->integer('total_capacity');
            $table->integer('registered_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_recurring')->default(false);
            $table->string('recurrence_pattern')->nullable(); // daily, weekly, monthly
            $table->string('timezone')->default('Asia/Kolkata');
            $table->string('language')->default('en');
            $table->boolean('requires_approval')->default(false);
            $table->json('tags')->nullable();
            $table->json('faqs')->nullable();
            $table->integer('views_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
