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
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('order_id')->nullable(false);
            // $table->string('payment_id')->nullable(false);
            $table->string('invoice_number');
            $table->string('customer_name');
            $table->decimal('invoice_amount'); // harus sudah include tax dari gateway nya
            $table->foreign('order_id')->references('id')->on('orders');
            $table->string('payment_id')->references('id')->on('payments');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
