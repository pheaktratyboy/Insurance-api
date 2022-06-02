<?php

namespace Database\Seeders;

use App\Enums\BaseRole;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MasterUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**@var Employee $staff*/
        $staff = Employee::factory()->create([
            'name_kh'     => 'master',
            'name_en'     => 'master',
        ]);

        /**@var User $user*/
        $user = $staff->user()->create([
            'username'          => 'master@master.com',
            'full_name'         => 'master',
            'email'             => 'master@master.com',
            'email_verified_at' => now(),
            'password'          => bcrypt('master!@#$'),
            'disabled'          => false,
            'activated'         => true,
            'activated_at'      => now(),
            'remember_token'    => Str::random(10),
        ]);

        /** assign role */
        $user->assignRole(BaseRole::Master);
    }
}
