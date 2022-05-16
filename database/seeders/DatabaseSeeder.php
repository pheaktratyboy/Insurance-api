<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Artisan::call('passport:install');
        $this->call(BaseRoleSeeder::class);
        $this->call(MasterUserSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(AddressSeeder::class);

        if (!app()->environment('production')) {
            $this->call(UserSeeder::class);
        }
    }
}
