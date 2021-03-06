<?php

namespace Database\Factories;

use App\Enums\Gender;
use App\Enums\Religion;
use App\Models\District;
use App\Models\Municipality;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name_kh'            => "{$this->faker->firstName} {$this->faker->lastName}",
            'name_en'            => "{$this->faker->firstName} {$this->faker->lastName}",
            'gender'             => $this->faker->randomElement(Gender::getValues()),
            'religion'           => $this->faker->randomElement(Religion::getValues()),
            'identity_number'    => $this->faker->unique()->randomDigit,
            'date_of_birth'      => $this->faker->date,

            'address'             => $this->faker->address,
            'place_of_birth'      => $this->faker->address,
            'municipality_id'     => Municipality::factory(),
            'district_id'         => District::factory(),

            'kpi'                 => "10",
            'phone_number'        => $this->faker->phoneNumber,
        ];
    }
}
