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
        Schema::create('tblanswer', function (Blueprint $table) {
            $table->id('answer_ID');
            $table->text('choices_desc');
            $table->tinyInteger('answer');
            $table->unsignedBigInteger('question_ID');
            $table->foreign('question_ID')->references('question_ID')->on('tblquestion')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('tblanswer');

    }
};
