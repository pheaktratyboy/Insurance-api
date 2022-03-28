<?php

namespace Database\Factories;

use App\Enums\BaseRole;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Role::class;


    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->randomElement(BaseRole::getValues());

        $role = strtolower("{$name}{$this->faker->randomLetter}");

        return [
            'name'  => $role,
            'label' => Str::of($role)->replace('_', ' ')->title(),
        ];
    }

    public function master()
    {
        return $this->state(function () {
            return ['name' => BaseRole::Master];
        });
    }
}
