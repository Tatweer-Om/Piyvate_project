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
        Schema::create('session_details', function (Blueprint $table) {
            $table->id();
            $table->integer('session_id');
            $table->string('session_type')->nullable();
            $table->integer('ministry_id')->nullable();
            $table->integer('offer_id')->nullable();
            $table->integer('patient_id');
            $table->integer('doctor_id');
            $table->decimal('total_fee', 10, 2);
            $table->integer('total_sessions');
            $table->string('contract_payment')->nullable()->comment('1 = Pending, 2 = Completed');

            $table->decimal('single_session_price', 10, 2);
            $table->json('session_data'); // Stores session details in JSON format
            $table->integer('user_id');
            $table->integer('added_by');
            $table->integer('branch_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_details');
    }
};
