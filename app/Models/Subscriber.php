<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use StatusType;

class Subscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_kh',
        'name_en',
        'identity_number',
        'date_of_birth',
        'primary_phone',
        'address',
        'place_of_birth',
        'gender',
        'category',
        'avatar_url',
        'id_or_passport',
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
    public function policies()
    {
        return $this->hasMany(SubscriberPolicy::class);
    }

    public function createNewSubscriberWithPolicies($request)
    {
        $user = auth()->user();

        $orderInfo['status'] = StatusType::approved();
        $orderInfo['user_id'] = $user->id;

        $this->createNewSubscriber($request)->addSubscriberPolicy($request);

        return $this;
    }

    public function createNewSubscriber(array $orderInfo)
    {
        $this->fill($orderInfo);
        $this->save();
        return $this;
    }

    public function addSubscriberPolicy($items)
    {
        $newItems = collect($items)->map(function ($item)  {
            $policies = new SubscriberPolicy;
            $policies->fill($item);

            return $policies;
        });

        $this->policies()->saveMany($newItems);

        return $this;
    }
}
