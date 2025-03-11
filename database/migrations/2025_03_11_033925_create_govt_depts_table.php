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
        Schema::create('govt_depts', function (Blueprint $table) {
            $table->id();
            $table->string('govt_name'); // Changed from category_name
            $table->longText('notes')->nullable();
            $table->string('govt_phone')->nullable();
            $table->string('govt_email')->nullable();
            $table->string('user_id', 255)->nullable();
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
        Schema::dropIfExists('govt_depts');
    }
};
