<?php

namespace Database\Factories;

use App\Models\Attendee;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Attendee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'adults' => $this->faker->numberBetween(0,5),
            'lions' => $this->faker->numberBetween(0,5),
            'kangaroos' => $this->faker->numberBetween(0,5),
            'babies' => $this->faker->numberBetween(0,5),
        ];
    }
}
