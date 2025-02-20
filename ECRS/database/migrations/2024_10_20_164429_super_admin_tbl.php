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
        Schema::create('super_admin_tbl', function (Blueprint $table) {
            $table->id('superAdminID');
            $table->string('Lname');
            $table->string('Fname');
            $table->string('Mname')->nullable();
            $table->string('Sname')->nullable();
            $table->tinyInteger('isActive')->nullable();
            $table->string('salutation')->nullable();
            $table->string('signature')->nullable();
            $table->foreignId('loginID')->references('loginID')->on('login_tbl');
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
