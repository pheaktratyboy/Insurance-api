<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name_kh', 'name_en', 'avatar_url', 'gender',
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
