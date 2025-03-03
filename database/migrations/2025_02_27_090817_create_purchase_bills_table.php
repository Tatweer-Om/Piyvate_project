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
        Schema::create('purchase_bills', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_id')->nullable();
            $table->string('invoice_no')->nullable();
            $table->decimal('total_price',50,3)->nullable();
            $table->decimal('total_shipping',50,3)->nullable();
            $table->decimal('grand_total',50,3)->nullable();
            $table->decimal('remaining_price',50,3)->nullable();
            $table->string('added_by')->nullable();
            $table->string('user_id', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_bills');
    }
};
