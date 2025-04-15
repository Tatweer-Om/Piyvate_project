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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');  
            $table->integer('payroll_type')->nullable();
            $table->integer('employee_type')->nullable();
            $table->decimal('amount', 15, 3)->nullable(); 
            $table->date('pay_date')->nullable();
            $table->string('payment_file')->nullable();
            $table->longText('notes')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('added_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
