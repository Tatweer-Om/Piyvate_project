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
        Schema::create('appointment_details', function (Blueprint $table) {
            $table->id();
            $table->integer('appointment_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('added_by')->nullable();
            $table->integer('branch_id')->nullable();
            $table->string('session_type')->nullable();
            $table->integer('ministry_id')->nullable();
            $table->integer('offer_id')->nullable();
            $table->integer('patient_id')->nullable();
            $table->integer('doctor_id')->nullable();
            $table->text('session_data')->nullable();
            $table->decimal('total_price', 10, 2)->nullable(); // Store session price
            $table->decimal('total_sessions', 10, 2)->nullable(); // Store session price
            $table->decimal('single_session_price', 10, 2)->nullable(); // Store session price
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_details');
    }
};
