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
        Schema::create('product_qty_histories', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('purchase_id')->nullable();
            $table->string('barcode')->nullable();
            $table->string('branch_id')->nullable();
            $table->string('source')->nullable()->comment('From where this data is coming e.g (purchase , sale etc)');
            $table->string('change_type')->comment('1: Plus qty , 2: Minus qty');
            $table->integer('previous_qty')->nullable();
            $table->integer('new_qty')->nullable();
            $table->integer('current_qty')->nullable();
            $table->text('notes')->nullable();
            $table->integer('status')->nullable()->default(1)->comment('1: New Request , 2: Updated Request');;
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
        Schema::dropIfExists('product_qty_histories');
    }
};
