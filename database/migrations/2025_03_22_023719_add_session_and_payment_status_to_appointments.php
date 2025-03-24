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
        Schema::table('appointments', function (Blueprint $table) {
            $table->integer('session_status')->nullable()->comment('1 = sessions recomended, 2 = Appointment, 0 = Default, 3=Total Sessions, 4=Cancelled 5=Pre-registered, 6=Direct-sessions');
            $table->integer('payment_status')->nullable()->comment('0 = Default, 1 = Normal Session, 2 = Offer Session, 3 = Contract Session');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            //
        });
    }
};
