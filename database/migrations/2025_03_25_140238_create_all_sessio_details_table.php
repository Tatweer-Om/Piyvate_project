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
        Schema::create('all_sessio_details', function (Blueprint $table) {
            $table->id();
            $table->integer('session_id'); // Removed foreign key constraint
            $table->string('patient_id'); // Removed foreign key constraint
            $table->date('session_date');
            $table->time('session_time');
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
        Schema::dropIfExists('all_sessio_details');
    }
};
