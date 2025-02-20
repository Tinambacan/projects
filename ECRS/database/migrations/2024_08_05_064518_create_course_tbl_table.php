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
        Schema::create('course_tbl', function (Blueprint $table) {
            $table->id('courseID');
            $table->string('courseCode');
            $table->string('courseTitle');
            $table->foreignId('programID')->references('programID')->on('program_tbl');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_tbl');
    }
};
