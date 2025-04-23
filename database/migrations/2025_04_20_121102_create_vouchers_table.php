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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->decimal('amount',10,3)->nullable();
            $table->longText('notes')->nullable();
            $table->integer('status')->nullable()->comment('1: new, 2: used');  // 'clinical_notes', 'neuro_assessments', etc.
            $table->string('added_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('branch_id')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
