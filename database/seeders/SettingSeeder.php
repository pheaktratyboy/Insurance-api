<?php

namespace Database\Seeders;

use App\Enums\SettingEnum;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createReminder();
    }


    private function createReminder()
    {
        Setting::create([
            'name'   => SettingEnum::Reminder,
            'option' => 1,
        ]);
    }
}
