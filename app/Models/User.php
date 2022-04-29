<?php

namespace App\Models;

use App\Traits\Blamable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use HasFactory, Blamable, Notifiable, HasApiTokens, HasRoles, InteractsWithMedia;

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
        'remember_token'
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function profile()
    {
        return $this->morphTo(null, 'profileable_type', 'profileable_id');
    }

    /**
     * @param $role_id
     * @return $this
     */
    public function updateHasRule($role_id) {
        $role = Role::firstWhere('id', $role_id);
        $this->assignRole($role->name);

        return $this;
    }

    /**
     * @param $array
     * @return $this
     */
    public function isUpdateIfHasName($array) {

        if (count($array)) {
            $this->update(['full_name' => $array['name_en']]);
        }

        return $this;
    }

    /**
     * @param $disabled
     * @return $this
     */
    public function isUpdateEnableOrDisabled($disabled) {

        if (count($disabled)) {
            $this->update($disabled);
        }

        return $this;
    }

    /**
     * @param $array
     * @return $this
     */
    public function updateIsNotActivate($array) {

        if (!$this->activated && count($array)) {

            $this->update([
                'activated'     => $array['activated'],
                'activated_at'  => now(),
            ]);
        }

        return $this;
    }
}
