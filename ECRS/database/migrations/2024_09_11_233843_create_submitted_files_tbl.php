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
        Schema::create('submitted_files_tbl', function (Blueprint $table) {
            $table->id('fileID');
            $table->string('file')->nullable();
            $table->tinyInteger('status')->nullable();
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
