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
        Schema::table('payment_expenses', function (Blueprint $table) {
            $table->string('order_no')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_expenses', function (Blueprint $table) {
            $table->integer('order_no')->change(); // or whatever the original type was
        });
    }
};
