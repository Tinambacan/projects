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
        Schema::create('student_assessment_tbl', function (Blueprint $table) {
            $table->id('studentAssessmentID');
            $table->foreignId('studentID')->references('studentID')->on('student_tbl');
            $table->foreignId('assessmentID')->references('assessmentID')->on('assessment_tbl');
            $table->foreignId('classRecordID')->references('classRecordID')->on('class_record_tbl');
            $table->string('score');
            $table->string('remarks')->nullable();
            $table->tinyInteger('isRequestedToView');
            $table->tinyInteger('isRawScoreViewable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_assessment_tbl');
    }
};
