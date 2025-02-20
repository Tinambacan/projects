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
        Schema::create('feedback_tbl', function (Blueprint $table) {
            $table->id('feedbackID');
            $table->string('subject');
            $table->text('body');
            $table->timestamp('read_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->foreignId('studentID')->references('loginID')->on('login_tbl');
            $table->foreignId('loginID')->references('loginID')->on('login_tbl');
            $table->foreignId('classRecordID')->references('classRecordID')->on('class_record_tbl');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
