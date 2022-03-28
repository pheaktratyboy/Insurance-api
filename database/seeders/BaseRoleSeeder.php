<?php

namespace Database\Seeders;

use App\Enums\BaseRole;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BaseRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = BaseRole::getValues();

        foreach ($roles as $role) {
            Role::create([
                'base'  => true,
                'label' => Str::of($role)->replace('_', ' ')->title(),
                'name'  => $role,
            ]);
        }
    }
}
