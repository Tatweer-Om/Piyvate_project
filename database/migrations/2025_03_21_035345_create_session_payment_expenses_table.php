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
        Schema::create('session_payment_expenses', function (Blueprint $table) {
            $table->id();
            $table->integer('appointment_id');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('account_tax', 5, 2)->nullable(); // Commission percentage
            $table->decimal('account_tax_fee', 10, 2)->nullable(); // Calculated commission fee
            $table->integer('account_id');
            $table->integer('user_id');
            $table->integer('branch_id');
            $table->string('added_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_payment_expenses');
    }
};
