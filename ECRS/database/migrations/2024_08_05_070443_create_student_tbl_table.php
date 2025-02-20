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
        Schema::create('student_tbl', function (Blueprint $table) {
            $table->id('studentID');
            $table->string('studentNo');
            $table->string('studentFname');
            $table->string('studentLname');
            $table->string('studentMname')->nullable();
            $table->string('email');
            $table->string('mobileNo')->nullable();
            $table->string('remarks')->nullable();
            $table->foreignId('classRecordID')->references('classRecordID')->on('class_record_tbl');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_tbl');
    }
};
