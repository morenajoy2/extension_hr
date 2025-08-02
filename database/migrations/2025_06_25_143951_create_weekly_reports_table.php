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
        Schema::create('weekly_reports', function (Blueprint $table) {
            $table->id();
            $table->timestamps(); 
            $table->foreignId('requirement_id')->constrained('requirements')->onDelete('cascade'); //set this weekly id
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); //get user id for other infos
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade'); //get department_name select
            $table->foreignId('department_team_id')->constrained('department_teams')->onDelete('cascade'); //get team_name select
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade'); //get role select
            $table->date('from_date');
            $table->date('to_date');
            $table->integer('worked_hours');
            $table->integer('total_hours');
            $table->integer('remaining_hours');
            $table->string('doc_upload')->nullable();
            $table->string('pdf_upload')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_reports');
    }
};
