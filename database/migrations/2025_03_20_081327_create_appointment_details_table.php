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
            $table->string('contract_payment')->nullable()->comment('1 = Pending, 2 = Completed');
            $table->integer('doctor_id')->nullable();
            $table->text('session_data')->nullable();
            $table->longText('notes')->nullable();
            $table->string('session_cat')->nullable()->comment('OT, PT, CT');
            $table->string('sessions_reccomended')->nullable();
            $table->integer('ot_sessions')->default(0);
            $table->integer('pt_sessions')->default(0);
            $table->string('sessions_taken')->nullable();
            $table->string('session_gap')->nullable();
            $table->decimal('total_price', 10, 2)->nullable();
            $table->decimal('total_sessions', 10, 2)->nullable();
            $table->decimal('single_session_price', 10, 2)->nullable();
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
