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
        Schema::create('purchase_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('purchase_id');  // Use an integer for foreign key
            $table->string('supplier_name');
            $table->string('invoice_no');
            $table->decimal('total_amount', 15, 2);
            $table->decimal('remaining_amount', 15, 2);
            $table->decimal('paid_amount', 15, 2);
            $table->integer('account_id');  // Use an integer for foreign key
            $table->date('payment_date');
            $table->date('purchase_date');
            $table->text('notes')->nullable();
            $table->string('payment_file')->nullable();
            $table->string('user_id', 255)->nullable();
            $table->string('added_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_payments');
    }
};
