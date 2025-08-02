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
        Schema::create('turnovers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requirement_id')->constrained('requirements')->onDelete('cascade'); //set this exit clearance id
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); //get user id for other infos
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade'); //get department_name select
            $table->foreignId('department_team_id')->constrained('department_teams')->onDelete('cascade'); //get team_name select
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade'); //get role select
            $table->foreignId('employment_type_id')->constrained('employment_types')->onDelete('cascade'); //get type_name select
            $table->string('job_title')->nullable();
            $table->date('orientation_date');
            $table->date('first_day_date');
            $table->date('last_day_date');
            $table->date('exit_date');
            $table->integer('total_worked_hours_required');

            $table->integer('recommended_employee_id');
            $table->string('recommended_employee_name');
            $table->text('task_list')->nullable();

            $table->string('new_owner_transfer_list')->nullable();
            $table->string('confirmation_access_credentials')->nullable();

            $table->integer('dpt_team_leader_employee_id');
            $table->string('dpt_team_leader_employee_name');  
            $table->integer('hr_team_leader_employee_id');
            $table->string('hr_team_leader_employee_name');  
            
            $table->string('e_signature');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turnover');
    }
};
