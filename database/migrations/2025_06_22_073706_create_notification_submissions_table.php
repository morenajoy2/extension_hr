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
        Schema::create('notification_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');   //get user info
            $table->foreignId('group_id')->nullable()->constrained('groups')->onDelete('set null'); //get group_no
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');   //get department_name
            $table->foreignId('position_id')->nullable()->constrained('positions')->onDelete('set null');   //get position_name
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null');   //get role
            $table->enum('percent_completed', ['25', '50', '75', '100']);
            $table->date('percent_date');
            $table->integer('total_worked_hours_completed');
            $table->integer('total_worked_hours_required');
            $table->foreignId('requirement_id')->nullable()->constrained('requirements')->onDelete('set null');
            $table->string('notifyFile');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_submissions');
    }
};
