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
        Schema::create('patient_prescriptions', function (Blueprint $table) {
            $table->id();
            $table->longText('notes')->nullable();
            $table->string('patient_id')->nullable();
            $table->string('appointment_id')->nullable();
            $table->string('prescription_type')->nullable();
            $table->string('ot_sessions')->nullable();
            $table->string('pt_sessions')->nullable();
            $table->string('session_cat')->nullable()->comment('OT, PT');
            $table->string('sessions_reccomended')->nullable();
            $table->string('sessions_taken')->nullable();
            $table->string('session_gap')->nullable();
            $table->longText('test_recommendations')->nullable();
            $table->string('user_id')->nullable();
            $table->string('added_by')->nullable();
            $table->string('branch_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_prescriptions');
    }
};
