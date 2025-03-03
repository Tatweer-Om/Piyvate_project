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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->nullable();
            $table->string('purchase_date')->nullable();
            $table->string('supplier_id')->nullable();
            $table->decimal('invoice_price',50,3)->nullable();
            $table->string('receipt_file')->nullable();
            $table->decimal('total_price',50,3)->nullable();
            $table->decimal('total_tax',50,3)->nullable();
            $table->decimal('total_shipping',50,3)->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('purchases');
    }
};
