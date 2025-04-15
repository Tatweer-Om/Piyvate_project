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
        Schema::create('appointment_payments', function (Blueprint $table) {
            $table->id();
            $table->string('appointment_id');
            $table->string('account_id');
            $table->string('payment_status')->comment('1=appointment-only, 2=appointment-sessions')->nullable;
            $table->string('session_payment')->comment('1=offer, 2=normal, 3=contract')->nullable();
            $table->string('ref_no')->nullable()->default('');
            $table->decimal('amount', 10, 2);
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
        Schema::dropIfExists('appointment_payments');
    }
};
