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
        Schema::create('event_promotion_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('duration_days');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('event_promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained('event_promotion_plans')->cascadeOnDelete();
            $table->decimal('amount_paid', 10, 2);
            $table->string('payment_status')->default('pending'); // pending, paid
            $table->string('status')->default('pending'); // pending, approved, rejected, expired
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_promotions');
        Schema::dropIfExists('event_promotion_plans');
    }
};
