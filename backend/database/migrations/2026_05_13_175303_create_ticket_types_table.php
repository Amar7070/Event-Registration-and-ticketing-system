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
        Schema::create('ticket_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Regular, VIP, Early Bird, etc.
            $table->text('description')->nullable();
            $table->enum('type', ['regular', 'vip', 'early_bird', 'student', 'group', 'premium'])->default('regular');
            $table->decimal('price', 10, 2)->default(0.00);
            $table->decimal('original_price', 10, 2)->nullable(); // for showing strikethrough
            $table->integer('quantity_total');
            $table->integer('quantity_sold')->default(0);
            $table->integer('max_per_order')->default(10);
            $table->integer('min_per_order')->default(1);
            $table->dateTime('sale_starts_at')->nullable();
            $table->dateTime('sale_ends_at')->nullable();
            $table->json('perks')->nullable(); // ["Free parking", "VIP lounge"]
            $table->boolean('is_active')->default(true);
            $table->boolean('is_transferable')->default(true);
            $table->boolean('is_refundable')->default(true);
            $table->integer('refund_days_before')->default(3); // refund allowed X days before event
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_types');
    }
};
