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
        Schema::create('sessions_payments', function (Blueprint $table) {
            $table->id();
            $table->string('appointment_id');
            $table->string('account_id')->nullable(); // Assuming accounts store payment methods
            $table->decimal('amount', 10, 2)->nullable();
            $table->integer('user_id');
            $table->string('ref_no')->nullable()->default('');
            $table->string('contract_payment')->nullable()->comment('1 = Pending, 2 = Completed');
            $table->integer('payment_status')->nullable()->comment('0 = Default, 1 = Normal Session, 2 = Offer Session, 3 = Contract Pending');
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
        Schema::dropIfExists('sessions_payments');
    }
};
