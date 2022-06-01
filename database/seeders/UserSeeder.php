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
        $staff = Employee::factory()->create([
            'name_kh'     => 'admin',
            'name_en'     => 'admin',
        ]);

        /**@var User $user*/
        $user = $staff->user()->create([
            'username'          => 'admin@cmdf.com',
            'full_name'         => 'admin',
            'email'             => 'admin@cmdf.com',
            'email_verified_at' => now(),
            'password'          => bcrypt('admin@cmdf'),
            'disabled'          => false,
            'activated'         => true,
            'activated_at'      => now(),
            'remember_token'    => Str::random(10),
        ]);

        /** assign role */
        $user->assignRole(BaseRole::Admin);
    }
}
