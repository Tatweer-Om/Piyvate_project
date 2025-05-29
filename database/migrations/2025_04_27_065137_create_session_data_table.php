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
        Schema::create('session_data', function (Blueprint $table) {
            $table->id();
            $table->integer('main_session_id')->nullable();
            $table->integer('main_appointment_id')->nullable();
            $table->string('session_cat')->nullable();
            $table->string('patient_id');
            $table->string('doctor_id')->nullable();
            $table->string('contract_payment')->nullable()->comment('1 = Pending, 2 = Completed');
            $table->date('session_date')->nullable();
            $table->time('session_time')->nullable();
            $table->decimal('session_price', 10, 2);
            $table->string('status')
                  ->default('1')
                  ->comment('1 = Pending, 2 = Completed, 3 = Transferred');
            $table->string('source')
                  ->default('0')
                  ->comment('1 = Direct, 2 = Appointment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_data');
    }


};
