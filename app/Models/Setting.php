<?php

namespace App\Models;

use App\Enums\SettingEnum;
use App\Traits\Blamable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory, Blamable;

    protected $fillable = ['name','option'];
    protected $casts    = ['option' => 'array'];

    /**
     * @param $query
     * @return mixed
     */
    public function scopeReminder($query)
    {
       return $query->where('name', SettingEnum::Reminder)->orderBy('id', 'desc')->first();
    }
}
