<?php

namespace App\Models;

use App\Enums\StatusType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

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
        'avatar',
        'id_or_passport_front',
        'id_or_passport_back',
        'user_id',
        'status',
        'expired_at',
    ];

    protected $casts = [
        'user_id'               => 'integer',
        'avatar'                => 'array',
        'id_or_passport_front'  => 'array',
        'id_or_passport_back'   => 'array',
    ];

    protected $dates = [
        'date_of_birth',
        'expired_at',
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
    public function createNewSubscriber(Request $request)
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
    public function addSubscriberPolicy(Request $request)
    {
        $sub_policy = new SubscriberPolicy($request->input());

        if ($request->has('policy_id')) {

            $policy = Policy::firstWhere('id', $request->policy_id);
            if ($policy) {

                $duration = $policy->duration;
                $newDateTime = Carbon::now()->addMonths($duration);
                $sub_policy->expired_at = $newDateTime;
            }
        }

        $this->subscriber_policies()->save($sub_policy);

        return $this;
    }
}
