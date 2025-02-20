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
        Schema::create('tblregistration', function (Blueprint $table) {
            $table->id('registration_ID');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->tinyInteger('role');
            $table->timestamps();
            $table->unsignedBigInteger('login_ID');
            $table->foreign('login_ID')->references('login_ID')->on('tbllogin')->onUpdate('cascade')->onDelete('cascade');
            $table->boolean('isActive');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblregistration');
    }
};
