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
        $policy = new SubscriberPolicy($request->validated());
        $this->subscriber_policies()->save($policy);

        return $this;
    }


    public function formatItemForPolicies($items)
    {
        $newItems = [];
        foreach ($items as $item) {

            $newItems[] = [
                'policy_id'        => $item->policy_id,
                'payment_method'   => $item->payment_method,
            ];
        }

        return $newItems;
    }


    public function addSubscriberPolicies($items)
    {
        $newItems = collect($items)->map(function ($item)  {
            $policies = new SubscriberPolicy;
            $policies->fill($item);

            return $policies;
        });

        $this->subscriber_policies()->saveMany($newItems);

        return $this;
    }
}
