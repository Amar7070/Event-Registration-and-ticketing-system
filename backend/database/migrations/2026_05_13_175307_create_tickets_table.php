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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('booking_reference')->unique(); // e.g., SE-2024-XXXXXX
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('ticket_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Payment will be created in phase 4, but we need to reference it.
            // For now, let's just make it a bigInteger nullable to avoid foreign key errors if payments table doesn't exist yet, or constrained if it does.
            // Setup.md says: $table->foreignId('payment_id')->nullable()->constrained()->onDelete('set null');
            // We will add constrained when payments table is ready or just create it now without constrained.
            $table->unsignedBigInteger('payment_id')->nullable();
            
            $table->foreignId('coupon_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'refunded', 'used'])->default('pending');
            $table->string('qr_code_path')->nullable();
            $table->string('qr_token')->unique();
            $table->boolean('is_transferred')->default(false);
            $table->foreignId('transferred_to')->nullable()->constrained('users');
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
