<?php

namespace App\Models;

use App\Enums\BaseRole;
use App\Enums\StatusType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_kh',
        'name_en',
        'identity_number',
        'date_of_birth',
        'phone_number',
        'address',
        'place_of_birth',
        'gender',
        'religion',
        'avatar_url',
        'id_or_passport_front',
        'id_or_passport_back',
        'user_id',
        'status',
    ];

    protected $casts = [
        'user_id'  => 'integer',
    ];

    protected $dates = [
        'date_of_birth',
        'created_at',
        'updated_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriber_policies()
    {
        return $this->hasMany(SubscriberPolicy::class);
    }

    /**
     * @param $request
     * @return $this
     */
    public function createNewSubscriber($request)
    {
        $user = auth()->user();
        $request['status'] = StatusType::Approved;
        $request['user_id'] = $user->id;

        $this->fill($request->input());
        $this->save();

        return $this;
    }

    /**
     * @param $request
     * @return $this
     */
    public function addSubscriberPolicy($request)
    {
        $policy = new SubscriberPolicy($request->input());

        $this->subscriber_policies()->save($policy);

        return $this;
    }
}
