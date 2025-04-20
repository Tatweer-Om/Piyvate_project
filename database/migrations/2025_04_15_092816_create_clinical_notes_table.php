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
        Schema::create('clinical_notes', function (Blueprint $table) {
            $table->id();
            $table->string('doctor_id')->nullable();
            $table->string('patient_id')->nullable();
            $table->string('appointment_id')->nullable();
            $table->string('form_type')->nullable();  // 'clinical_notes', 'neuro_assessments', etc.
            $table->string('notes_status')->nullable();
            $table->longText('form_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinical_notes');
    }
};
