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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('clinic_no')->unique();
            $table->string('first_name');
            $table->string('second_name')->nullable();
            $table->string('full_name');
            $table->string('mobile')->nullable();
            $table->string('id_passport')->unique(); // Unique identifier for each patient
            $table->date('dob')->nullable();
            $table->string('branch_id')->nullable();
            $table->string('added_by')->nullable();
            $table->string('user_id')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
