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
        Schema::create('session_transfer_logs', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->nullable();
            $table->string('source_table')->nullable();
            $table->string('old_patient_id')->nullable();
            $table->string('new_patient_id')->nullable();
            $table->string('transferred_by')->nullable();
            $table->string('user_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_transfer_logs');
    }
};
