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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
        
            $table->integer('employee_id');  
            $table->integer('leaves_type')->nullable();
            $table->integer('employee_type')->nullable();
            $table->integer('total_leaves')->nullable(); 
            $table->integer('remaining_leaves')->nullable(); 
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->string('leave_file')->nullable();
            $table->longText('reason')->nullable();
        
            // Leave status: 1 = pending, 2 = accepted, 3 = rejected
            $table->integer('status')->default(1)->comment('1 = pending, 2 = accepted, 3 = rejected');
        
            $table->integer('branch_id')->nullable();       // branch id
            $table->integer('user_id')->nullable();       // Person who applied
            $table->string('added_by')->nullable();       // Can be admin/staff ID or name
            $table->integer('responded_by')->nullable();  // Approver ID
            $table->timestamp('responded_date')->nullable();
        
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
