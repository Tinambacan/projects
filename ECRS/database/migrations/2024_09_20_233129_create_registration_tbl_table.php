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
        Schema::create('registration_tbl', function (Blueprint $table) {
            $table->id('registrationID');
            $table->string('Lname');
            $table->string('Fname');
            $table->string('Mname')->nullable();
            $table->string('Sname')->nullable();
            $table->string('salutation')->nullable();
            $table->tinyInteger('role');
            $table->tinyInteger('branch');
            $table->string('schoolIDNo');
            $table->tinyInteger('isActive')->nullable();
            $table->tinyInteger('isSentCredentials')->nullable();
            $table->string('signature')->nullable();
            $table->foreignId('loginID')->references('loginID')->on('login_tbl');
            $table->foreignId('adminID')->references('loginID')->on('login_tbl');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_tbl');
    }
};
