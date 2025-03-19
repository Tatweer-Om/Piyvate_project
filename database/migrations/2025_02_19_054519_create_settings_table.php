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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('appointment_fee')->nullable();
            $table->string('company_name')->nullable(); // Company Name
            $table->string('company_email')->nullable(); // Company Email
            $table->string('company_phone')->nullable(); // Company Phone
            $table->string('company_cr')->nullable(); // Company CR
            $table->string('company_address')->nullable(); // Company Address
            $table->longText('notes')->nullable(); // Notes
            $table->string('logo')->nullable();
            $table->string('added_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('user_id');
            $table->string('branch_id', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
