<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('audit_trail', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('record_id')->nullable();
            $table->string('user', 50);
            $table->string('action', 50);
            $table->string('table_name', 50);
            $table->text('old_value')->nullable(); // To store old values (used in update)
            $table->text('new_value')->nullable(); // To store new values
            $table->text('description')->nullable();
            $table->timestamp('action_time')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_trail');
    }
};

