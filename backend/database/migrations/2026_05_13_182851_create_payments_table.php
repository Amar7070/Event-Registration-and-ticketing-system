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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('payment_reference')->unique(); // Internal ref
            $table->string('gateway_payment_id')->nullable(); // Stripe/Razorpay ID
            $table->string('gateway_order_id')->nullable();
            $table->enum('gateway', ['stripe', 'razorpay', 'paypal', 'free'])->default('stripe');
            $table->decimal('amount', 10, 2);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('platform_fee', 10, 2)->default(0);
            $table->decimal('organizer_earnings', 10, 2)->default(0);
            $table->string('currency', 3)->default('INR');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'refunded', 'partial_refund'])->default('pending');
            $table->json('gateway_response')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
