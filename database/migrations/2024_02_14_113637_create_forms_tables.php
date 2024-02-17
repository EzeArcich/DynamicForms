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
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('fields', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('form_id');
            $table->string('name');
            $table->enum('type', ['text', 'number', 'date', 'option']);
            $table->json('options')->nullable();
            $table->foreign('form_id')->references('id')->on('forms');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fields');
        Schema::dropIfExists('forms');
    }
};
