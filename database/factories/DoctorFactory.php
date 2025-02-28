<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doctor>
 */
class DoctorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'=>fake()->name(),
            'gender'=>fake()->randomElement(['male','female']),
            'email'=>fake()->email(),
            'password'=>fake()->password(),
            'phone_number'=>fake()->phoneNumber(),
            'speciality'=>fake()->randomElement(['genralist','dentist','Cardiologist','Neurologist','Orthopedic','Dermatologist','Gynecologist']),
            'formations'=>fake()->realText(12),
            'type_consultation'=>fake()->randomElement(['none','text','video','all']),
            'city'=>fake()->city(),
            'street'=>fake()->realText(11),
            'localisation'=>fake()->realText(11),
            'rating'=>fake()->randomFloat(1,0,5),
            'approved'=>fake()->boolean(),
        ];
    }
}
