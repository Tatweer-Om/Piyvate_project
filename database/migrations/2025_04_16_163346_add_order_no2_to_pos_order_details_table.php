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
        Schema::table('pos_order_details', function (Blueprint $table) {
            $table->string('order_no2')->nullable()->after('order_id'); // or change the type if needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pos_order_details', function (Blueprint $table) {
            
        });
    }
};
