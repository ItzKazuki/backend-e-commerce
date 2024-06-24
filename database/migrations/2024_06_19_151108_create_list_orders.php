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
            $table->string('id')->primary();
            $table->uuid('user_id')->nullable(false);
            $table->string('invoice_number');
            $table->string('payment_method');
            $table->string('payment_status');
            $table->string('payment_amount');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('payment_id')->nullable(false);
            $table->uuid('customer_id')->nullable(false);
            $table->string('shipping_address');
            $table->decimal('shipping_cost');
            $table->string('payment_method');
            $table->string('order_status');
            $table->decimal('total_price');
            $table->foreign('customer_id')->references('id')->on('users');
            $table->foreign('payment_id')->references('id')->on('payments');
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('payment_id')->nullable(false);
            $table->string('invoice_number');
            $table->string('customer_name');
            $table->decimal('invoice_amount'); // harus sudah include tax dari gateway nya
            $table->foreign('payment_id')->references('id')->on('payments');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('payments');
    }
};
