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
        Schema::create('sations', function (Blueprint $table) {
            $table->id();
            $table->string('session_name')->nullable();
            $table->string('session_price')->nullable();
            $table->string('session_type')->nullable();
            $table->string('ministry_cat_id')->nullable();
            $table->longText('notes')->nullable();
            $table->string('government_id', 255)->nullable();
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
        Schema::dropIfExists('sations');
    }
};
