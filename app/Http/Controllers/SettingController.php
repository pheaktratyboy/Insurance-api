<?php

namespace App\Http\Controllers;

use App\Enums\SettingEnum;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{

    public function index()
    {
        $company  = Setting::where('name', SettingEnum::Company)->orderBy('id', 'desc')->first();
        $reminder = Setting::where('name', SettingEnum::Reminder)->orderBy('id', 'desc')->first();

        return response()->json([
            'data' => [
                SettingEnum::Company    => $company,
                SettingEnum::Reminder   => $reminder,
            ],
        ]);
    }


    public function getReminder()
    {
        $reminder = Setting::where('name', SettingEnum::Reminder)->orderBy('id', 'desc')->first();

        return response()->json([
            'data' => [
                'reminder' => $reminder,
            ],
        ]);
    }

    public function getCompany()
    {
        $setting = Setting::firstWhere('name', SettingEnum::Company);
        if (!$setting) {
            return response()->json(['data' => [],]);
        }

        return response()->json([
            'data' => $setting['option'],
        ]);
    }

    public function updateReminder(Request $request)
    {
        $request->validate([
            'reminder' => 'required',
        ]);

        $reminder = Setting::where('name', SettingEnum::Reminder)->orderBy('id', 'desc')->first();
        $reminder->update(['option' => $request->reminder,]);

        return response()->json([
            'data' => [
                'reminder' => $reminder,
            ],
        ]);
    }
}
