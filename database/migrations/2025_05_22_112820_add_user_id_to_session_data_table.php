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
        Schema::table('session_data', function (Blueprint $table) {
            $table->string('user_id')->nullable()->after('created_at'); // add after 'id' or any other column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('session_data', function (Blueprint $table) {
            //
        });
    }
};
