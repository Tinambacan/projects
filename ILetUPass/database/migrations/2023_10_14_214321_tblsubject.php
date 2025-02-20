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
        Schema::create('tblsubject', function (Blueprint $table) {
            $table->id('subject_ID');
            $table->string('subject_name');
            $table->string('subject_desc');
            $table->string('subject_image')->nullable(); // Add the new image column
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblsubject');
    }
};
