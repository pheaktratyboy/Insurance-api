<?php

namespace App\Models;

use App\Traits\Blamable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory, Blamable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name_kh',
        'name_en',
        'avatar_url',
        'gender',
        'identity_number',
        'date_of_birth',
        'primary_phone',
        'address',
        'place_of_birth',
        'category',
        'commission',
        'kpi',
        'municipality_id',
        'district_id',
    ];

    protected $casts = [
        'municipality_id'   => 'integer',
        'district_id'       => 'integer',
    ];

    protected $dates = [
        'date_of_birth',
        'created_at',
        'updated_at',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function user()
    {
        return $this->morphOne(User::class, 'profilable');
    }

    /**
     * @param $request
     * @return Model
     */
    public function openAccount($request)
    {
        return $this->user()->create([
            'username'                       => $request['username'],
            'password'                       => bcrypt($request['password']),
            'disabled'                       => false,
            'required_password_update'       => $request['required_password_update'],
        ]);
    }
}
