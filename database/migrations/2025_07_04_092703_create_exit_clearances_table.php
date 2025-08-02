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
        Schema::create('exit_clearances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requirement_id')->constrained('requirements')->onDelete('cascade'); //set this exit clearance id
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); //get user id for other infos
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade'); //get department_name select
            $table->foreignId('department_team_id')->constrained('department_teams')->onDelete('cascade'); //get team_name select
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade'); //get role select
            $table->foreignID('group_id')->constrained('groups')->onDelete('cascade'); //get group select
            $table->enum('exit_type', ['completion', 'resignation', 'termination']);
            $table->foreignId('task_turnover_role')->constrained('roles')->onDelete('cascade');
            $table->string('task_list')->nullable();
            $table->string('team_leader_access_confirmation');
            $table->string('hr_access_confirmation');
            $table->string('e_signature');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exit_clearances');
    }
};
