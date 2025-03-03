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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_id')->nullable();
            $table->string('invoice_no')->nullable();

            $table->string('store_id');
            $table->string('category_id');

            $table->string('supplier_id');
            $table->string('product_id');
            $table->string('product_name')->nullable();
            $table->string('product_name_ar')->nullable();
            $table->string('barcode')->nullable();
            $table->decimal('purchase_price',50,3)->nullable();
            $table->decimal('profit_percent',50,3)->nullable();
            $table->decimal('sale_price',50,3)->nullable();
            $table->decimal('min_sale_price',50,3)->nullable();
            $table->decimal('total_purchase',50,3)->nullable();
            $table->integer('tax')->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('product_type')->nullable()->comment('1: Hospital, 2: Sales');
            $table->text('description')->nullable();
            $table->string('stock_image')->nullable();
            $table->string('added_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('user_id', 255)->nullable();
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
