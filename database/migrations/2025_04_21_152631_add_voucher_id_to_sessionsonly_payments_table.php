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
        Schema::table('sessionsonly_payments', function (Blueprint $table) {
            $table->integer('voucher_id')->nullable()->after('ref_no');
            $table->string('voucher_code')->nullable()->after('voucher_id');
            $table->decimal('voucher_amount', 10, 3)->nullable()->after('voucher_code');
            $table->string('voucher_added')->nullable()->after('voucher_amount');
            $table->integer('voucher_user_id')->nullable()->after('voucher_added');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sessionsonly_payments', function (Blueprint $table) {
            //
        });
    }
};
