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
        Schema::create('soap_o_t_s', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->nullable(); // optional: link to a session if needed
            $table->string('main_session_id')->nullable(); // optional: link to a session if needed
            $table->string('main_appointment_id')->nullable(); // optional: link to a session if needed
            $table->string('patient_id')->nullable(); // optional: link to a session if needed
            $table->string('doctor_id')->nullable();
            $table->string('pt')->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->string('bp')->nullable();
            $table->string('pulse')->nullable();
            $table->string('o2sat')->nullable();
            $table->string('temp')->nullable();
            $table->string('ps')->nullable();
            $table->longText('s')->nullable();
            $table->longText('o')->nullable();
            $table->longText('a')->nullable();
            $table->longText('p')->nullable();
            $table->string('number')->nullable();
            $table->string('signature')->nullable();
            $table->string('branch_id')->nullable();
            $table->string('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soap_o_t_s');
    }
};
