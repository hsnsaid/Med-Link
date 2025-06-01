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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->string('gender')->enum('male','female');
            $table->string('phone_number');
            $table->string('speciality')->enum('Genralist','Dentistry','Cardiologist','Neurologist','ENT','Dermatologist','Orthopedic','Gynecologist','Pediatrician','Ophthalmologist','Psychiatrist','Urologist');            $table->string('city');
            $table->string('street');
            $table->decimal('rating',2,1)->default(0);
            $table->string('picture')->nullable();
            $table->decimal('balance',2,1)->default(0);
            $table->boolean('approved')->default(false);
            $table->string('status')->default('offline');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
