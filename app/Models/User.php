<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email' , 'password', 'disabled', 'activated', 'required_password_update', 'timezone',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'disabled'                      => 'boolean',
        'activated'                     => 'boolean',
        'required_password_update'      => 'boolean',
    ];


    public function findForPassport($username)
    {
        return $this
            ->where('email', $username)
            ->where('disabled', false)
            ->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function profile()
    {
        return $this->morphTo(null, 'profilable_type', 'profilable_id');
    }

    public function scopeJoinProfile($query)
    {
        return $query->join('employees', 'employees.id', '=', 'users.profilable_id');
    }
}
