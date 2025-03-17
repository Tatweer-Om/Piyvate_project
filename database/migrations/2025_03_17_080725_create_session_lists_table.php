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
        Schema::create('session_lists', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('first_name')->nullable();
            $table->string('second_name')->nullable();
            $table->string('full_name')->nullable();
            $table->string('mobile')->nullable();
            $table->string('id_passport')->nullable();
            $table->date('dob')->nullable();
            $table->string('country')->nullable();// Storing country name as a string
            $table->string('doctor')->nullable(); // Storing doctor name as a string
            $table->string('session_type')->nullable();// e.g., 'pact', 'ministry', 'offer'
            $table->string('session_fee')->nullable();// e.g., 'pact', 'ministry', 'offer'
            $table->integer('no_of_sessions')->default(1);
            $table->integer('session_gap')->nullable();
            $table->date('session_date');
            $table->string('offer_id')->nullable(); // Storing offer ID as a string
            $table->string('ministry_id')->nullable(); // Storing ministry ID as a string
            $table->string('session_cat')->nullable(); // Storing ministry ID as a string
            $table->text('notes')->nullable();
            $table->string('user_id', 255)->nullable();
            $table->string('branch_id', 255)->nullable();
            $table->string('added_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_lists');
    }
};
