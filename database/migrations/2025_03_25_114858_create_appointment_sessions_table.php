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
        Schema::create('appointment_sessions', function (Blueprint $table) {
            $table->id();
            $table->integer('appointment_id'); // Removed foreign key constraint
            $table->string('patient_id'); // Removed foreign key constraint
            $table->string('doctor_id')->nullable(); // Removed foreign key constraint

            $table->date('session_date')->nullable();
            $table->time('session_time')->nullable();
            $table->decimal('session_price', 10, 2);
            $table->string('status')
                  ->default('1')
                  ->comment('1 = Pending, 2 = Completed, 3 = Transferred');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_sessions');
    }
};
