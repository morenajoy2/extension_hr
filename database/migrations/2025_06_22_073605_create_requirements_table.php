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
        Schema::create('requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type')->nullable();
            $table->string('file')->nullable();
            $table->enum('status', ['Incomplete', 'Pending', 'Completed'])->default('Incomplete');
            $table->date('upload_date')->nullable();
            $table->enum('requires_signature', ['Yes', 'No'])->default('No');
            $table->string('signed_file')->nullable();
            $table->enum('signature_status', ['Signed', 'Unsigned'])->default('Unsigned');
            $table->timestamp('signed_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requirements');
    }
};
