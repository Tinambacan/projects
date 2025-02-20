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
        Schema::create('class_record_tbl', function (Blueprint $table) {
            $table->id('classRecordID');
            $table->string('schoolYear');
            $table->tinyInteger('semester');
            $table->string('yearLevel');
            $table->string('classImg')->nullable();
            $table->tinyInteger('template')->nullable();
            $table->tinyInteger('recordType')->nullable();
            $table->tinyInteger('isArchived');
            $table->tinyInteger('isSubmitted');
            $table->tinyInteger('branch');
            $table->foreignId('programID')->references('programID')->on('program_tbl');
            $table->foreignId('courseID')->references('courseID')->on('course_tbl');
            $table->foreignId('loginID')->references('loginID')->on('login_tbl');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_record_tbl');
    }
};
