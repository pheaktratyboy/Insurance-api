<?php

namespace Database\Factories;

use App\Models\Municipality;
use Illuminate\Database\Eloquent\Factories\Factory;

class DistrictFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'municipality_id' => Municipality::factory(),
            'name'            => $this->faker->name,
        ];
    }
}
