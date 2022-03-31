<?php

namespace App\Models;

use App\Traits\Blamable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Blamable, Notifiable, HasApiTokens, HasRoles;

    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'full_name',
        'email' ,
        'password',
        'disabled',
        'activated',
        'activated_at',
        'required_password_update',
        'timezone',
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
        'force_change_password'         => 'boolean',
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
        return $this->morphTo(null, 'profileable_type', 'profileable_id');
    }

    public function scopeJoinProfile($query)
    {
        return $query->join('employees', 'employees.id', '=', 'users.profileable_id');
    }
}
