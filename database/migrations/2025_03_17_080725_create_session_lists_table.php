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
            $table->string('doctor_id')->nullable();
            $table->string('patient_id')->nullable();
            $table->string('session_no')->nullable();
            $table->string('HN')->nullable();
            $table->string('session_type')->nullable();
            $table->string('session_fee')->nullable();
            $table->integer('no_of_sessions')->default(1);
            $table->integer('session_gap')->nullable();
            $table->date('session_date');
            $table->string('offer_id')->nullable();
            $table->string('ministry_id')->nullable();
            $table->string('session_cat')->nullable();
            $table->text('notes')->nullable();
            $table->integer('session_status')->nullable()->comment('1 = sessions recomended, 2 = Session, 0 = Default, 3=Total Sessions, 4=Cancelled 5=Pre-registered');
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
        Schema::dropIfExists('session_lists');
    }
};
