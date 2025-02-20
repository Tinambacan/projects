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
        //
        Schema::create('tblquestion', function (Blueprint $table) {
            $table->id('question_ID');
            $table->longText('question_desc');
            $table->longText('question_exp')->nullable();
            $table->string('level');
            $table->unsignedBigInteger('subject_ID');
            $table->foreign('subject_ID')->references('subject_ID')->on('tblsubject')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('tblquestion');
    }
};
