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
        Schema::create('balances', function (Blueprint $table) {
            $table->id();
            $table->string('account_id')->nullable();
            $table->string('account_name')->nullable();
            $table->string('source')->nullable();
            $table->string('expense_name')->nullable();
            $table->string('expense_amount')->nullable();
            $table->string('expense_image')->nullable();
            $table->date('expense_date')->nullable();
            $table->string('account_no')->nullable();
            $table->decimal('previous_balance', 15, 2)->default(0);
            $table->date('balance_date')->nullable();
            $table->decimal('new_total_amount', 15, 2)->default(0);
            $table->decimal('new_balance', 15, 2)->default(0);
            $table->string('balance_image')->nullable();
            $table->longText('notes')->nullable();
            $table->string('added_by')->nullable();  // stores user name
            $table->string('expense_added_by')->nullable();  // stores user name
            $table->string('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balances');
    }
};
