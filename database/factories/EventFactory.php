<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->streetName,
            'date' => $this->faker->unique()->date('Y-m-d','+90 days'),
            'description' => $this->faker->text(500),
            'max_adults' => $this->faker->numberBetween(0,20),
            'max_lions' => $this->faker->numberBetween(0,20),
            'max_kangaroos' => $this->faker->numberBetween(0,20),
            'max_babies' => $this->faker->numberBetween(0,20),
        ];
    }
}
