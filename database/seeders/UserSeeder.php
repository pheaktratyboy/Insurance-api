<?php

namespace Database\Seeders;

use App\Enums\BaseRole;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createAdmin();
    }

    private function createAdmin()
    {
        /**@var Employee $staff*/
        $staff = Employee::factory()->create();

        /**@var User $user*/
        $user = $staff->user()->create([
            'username'          => 'admin@admin.com',
            'email'             => 'admin@admin.com',
            'email_verified_at' => now(),
            'password'          => bcrypt('123456'),
            'disabled'          => false,
            'activated'         => true,
            'activated_at'      => now(),
            'remember_token'    => Str::random(10),
        ]);

        /** assign role */
        $user->assignRole(BaseRole::Admin);
    }
}
