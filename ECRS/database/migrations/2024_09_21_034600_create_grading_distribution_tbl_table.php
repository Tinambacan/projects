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
        Schema::create('grading_distribution_tbl', function (Blueprint $table) {
            $table->id('gradingDistributionID');
            $table->string('gradingDistributionType');
            $table->decimal('gradingDistributionPercentage');
            $table->tinyInteger('term');
            $table->tinyInteger('isPublished')->nullable();
            $table->foreignId('classRecordID')->references('classRecordID')->on('class_record_tbl');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grading_distribution_tbl');
    }
};
