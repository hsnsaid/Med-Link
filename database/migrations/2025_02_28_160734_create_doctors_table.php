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
            $table->string('password');
            $table->string('gender')->enum('male','female');
            $table->string('email');
            $table->string('phone_number');
            $table->string('speciality')->enum('genralist','dentist','Cardiologist','Neurologist','Orthopedic','Dermatologist','Gynecologist');
            $table->string('formations');
            $table->string('type_consultation')->enum('none','text','video','all');
            $table->string('city');
            $table->string('street');
            $table->string('localisation');
            $table->decimal('rating',2,1)->default(0);
            $table->string('picture')->nullable();
            $table->boolean('approved')->default(false);
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
