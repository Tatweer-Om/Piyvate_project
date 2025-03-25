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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('patient_id')->nullable();
            $table->string('clinic_no')->nullable();
            $table->string('appointment_no')->nullable();
            $table->string('doctor_id')->nullable();
            $table->date('appointment_date')->nullable();
            $table->string('appointment_fee')->nullable();
            $table->time('time_from')->nullable();
            $table->time('time_to')->nullable();
            $table->longText('notes')->nullable();
            $table->integer('session_status')->nullable()->comment('1 = sessions recomended, 2 = Appointment, 0 = Default, 3=Total Sessions, 4=Cancelled 5=Pre-registered, 6=Direct-sessions');
            $table->integer('payment_status')->nullable()->comment('0 = Default, 1 = Normal Session, 2 = Offer Session, 3 = Contract Session');
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
        Schema::dropIfExists('appointments');
    }
};
