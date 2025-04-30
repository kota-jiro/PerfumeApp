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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->date('sale_date');
            $table->string('status')->default('pending'); // Added status column
            $table->string('payment_method')->nullable(); // Added payment method column
            $table->string('transaction_id')->nullable(); // Added transaction ID column
            $table->string('shipping_address')->nullable(); // Added shipping address column
            $table->string('billing_address')->nullable(); // Added billing address column
            $table->string('customer_email')->nullable(); // Added customer email column
            $table->string('customer_phone')->nullable(); // Added customer phone column
            $table->string('tracking_number')->nullable(); // Added tracking number column
            $table->string('shipping_status')->default('not shipped'); // Added shipping status column
            $table->string('return_status')->default('not returned'); // Added return status column
            $table->string('refund_status')->default('not refunded'); // Added refund status column
            $table->string('discount_code')->nullable(); // Added discount code column
            $table->decimal('discount_amount', 10, 2)->nullable(); // Added discount amount column
            $table->string('notes')->nullable(); // Added notes column
            $table->string('created_by')->nullable(); // Added created by column
            $table->string('updated_by')->nullable(); // Added updated by column
            $table->string('deleted_by')->nullable(); // Added deleted by column
            $table->string('approved_by')->nullable(); // Added approved by column
            $table->string('rejected_by')->nullable(); // Added rejected by column
            $table->string('cancellation_reason')->nullable(); // Added cancellation reason column
            $table->string('cancellation_requested_by')->nullable(); // Added cancellation requested by column
            $table->string('cancellation_approved_by')->nullable(); // Added cancellation approved by column
            $table->string('cancellation_rejected_by')->nullable(); // Added cancellation rejected by column
            $table->string('cancellation_notes')->nullable(); // Added cancellation notes column
            $table->string('cancellation_status')->default('not cancelled'); // Added cancellation status column
            $table->string('cancellation_requested_at')->nullable(); // Added cancellation requested at column
            $table->string('cancellation_approved_at')->nullable(); // Added cancellation approved at column    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
