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
        Schema::create('tblscore', function (Blueprint $table) {
            $table->id('score_ID');
            $table->integer('score');
            $table->string('level');
            $table->unsignedBigInteger('login_ID');
            $table->foreign('login_ID')->references('login_ID')->on('tbllogin')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('subject_ID');
            $table->foreign('subject_ID')->references('subject_ID')->on('tblsubject')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblscore');
    }
};
