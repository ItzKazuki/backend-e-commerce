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
        Schema::create('uploads', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->uuid('user_id')->nullable(false);
            $table->string('image');
            $table->string('file_name');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('seller_id')->nullable(false);
            $table->string('upload_id');
            $table->string('product_name');
            $table->text('product_desc');
            $table->bigInteger('stock');
            $table->bigInteger('price');
            $table->string('brand');
            $table->foreign('seller_id')->references('id')->on('users');
            $table->foreign('upload_id')->references('id')->on('uploads')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
