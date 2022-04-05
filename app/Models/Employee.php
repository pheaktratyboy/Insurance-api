<?php

namespace App\Models;

use App\Traits\Blamable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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
        'gender',
        'identity_number',
        'date_of_birth',
        'phone_number',
        'address',
        'place_of_birth',
        'religion',
        'commission',
        'kpi',
        'municipality_id',
        'avatar',
        'id_or_passport_front',
        'id_or_passport_back',
        'district_id',
        'user_id',
    ];

    protected $casts = [
        'municipality_id'       => 'integer',
        'district_id'           => 'integer',
        'user_id'               => 'integer',
        'avatar'                => 'array',
        'id_or_passport_front'  => 'array',
        'id_or_passport_back'   => 'array',
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
        return $this->morphOne(User::class, 'profileable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function municipality()
    {
        return $this->belongsTo(Municipality::class, 'municipality_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
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
