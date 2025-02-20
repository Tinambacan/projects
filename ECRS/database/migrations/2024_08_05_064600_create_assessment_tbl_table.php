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
        Schema::create('assessment_tbl', function (Blueprint $table) {
            $table->id('assessmentID');
            $table->string('assessmentType');
            $table->string('assessmentName');
            $table->integer('totalItem')->nullable();
            $table->integer('passingItem')->nullable();
            $table->tinyInteger('term');
            $table->date('assessmentDate');
            $table->tinyInteger('isPublished');
            $table->foreignId('classRecordID')->references('classRecordID')->on('class_record_tbl');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_tbl');
    }
};
