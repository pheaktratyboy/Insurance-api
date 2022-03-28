<?php

namespace Database\Factories;

use App\Models\Municipality;
use Database\Factories\FakerProvider\KhmerProvince;
use Illuminate\Database\Eloquent\Factories\Factory;

class MunicipalityFactory extends Factory
{

    public function configure()
    {
        $this->faker->addProvider(new KhmerProvince($this->faker));

        return $this;
    }

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Municipality::class;


    /**
     * Define the model's default state.
     *
     * @return array
     */

    public function definition()
    {
        $name = $this->faker->unique()->province;

        return [
            'name' => $name
        ];
    }
}
