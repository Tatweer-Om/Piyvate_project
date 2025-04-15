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
        Schema::create('otppediatrics', function (Blueprint $table) {
            $table->id();
            $table->string('patient_id')->nullable();
            $table->string('appointment_id')->nullable();
            $table->string('doctor_id')->nullable();
            $table->string('hn')->nullable();
            $table->string('pt_no')->nullable();

            // Sections
            $table->longText('chief_complaint')->nullable();
            $table->longText('general_appearance')->nullable();
            $table->longText('birth_history')->nullable();
            $table->longText('behavioural_issues')->nullable();

            $table->longText('gross_motor')->nullable();
            $table->longText('fine_motor')->nullable();
            $table->longText('language')->nullable();
            $table->longText('personal_social')->nullable();
            $table->longText('cognitive_function')->nullable();

            $table->longText('vestibular')->nullable();
            $table->longText('proprioceptive')->nullable();
            $table->longText('tactile')->nullable();
            $table->string('muscle_tone_upper')->nullable();
            $table->string('muscle_tone_lower')->nullable();
            $table->longText('sensation')->nullable();
            $table->longText('rom')->nullable();
            $table->longText('hand_use')->nullable();
            $table->longText('oro_motor')->nullable();
            $table->longText('oral_reflexes')->nullable();
            $table->longText('adl')->nullable();
            $table->longText('visual_perception')->nullable();
            $table->longText('reflexes')->nullable();

            // Fall & pain assessment
            $table->string('fall_risk')->nullable();
            $table->string('pain_assessment')->nullable();
            $table->string('pain_score')->nullable();
            $table->string('pain_location')->nullable();
            $table->string('pain_duration')->nullable();
            $table->string('pain_characteristic')->nullable();
            $table->string('pain_frequency')->nullable();

            // Program info
            $table->longText('ot_diagnosis')->nullable();
            $table->longText('ot_program')->nullable();
            $table->longText('short_term_goal')->nullable();
            $table->longText('long_term_goal')->nullable();
            $table->longText('education')->nullable();
            $table->string('therapist_name')->nullable();
            $table->string('date')->nullable();
            $table->string('time')->nullable();

            // Pain tool
            $table->string('pain_tool')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otppediatrics');
    }
};
