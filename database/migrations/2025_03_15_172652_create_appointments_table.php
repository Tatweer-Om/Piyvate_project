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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('first_name')->nullable();
            $table->string('second_name')->nullable();
            $table->string('full_name')->nullable();
            $table->string('mobile')->nullable();
            $table->string('id_passport')->nullable();
            $table->date('dob')->nullable();
            $table->string('country_id')->nullable();
            $table->string('doctor_id')->nullable();
            $table->date('appointment_date')->nullable();
            $table->time('time_from')->nullable();
            $table->time('time_to')->nullable();
            $table->longText('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
