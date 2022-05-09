<?php

namespace App\Models;

use App\Enums\StatusType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        'attachments',
        'user_id',
        'company_id',
        'status',
        'note',
        'expired_at',
        'total',
    ];

    protected $casts = [
        'user_id'               => 'integer',
        'company_id'            => 'integer',
        'avatar'                => 'array',
        'id_or_passport_front'  => 'array',
        'id_or_passport_back'   => 'array',
        'attachments'           => 'array',
        'total'                 => 'decimal:2',
    ];

    protected $dates = [
        'date_of_birth',
        'expired_at',
        'created_at',
        'updated_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function user_profile()
    {
        return $this->morphOne(User::class, 'profileable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriber_policies()
    {
        return $this->hasMany(SubscriberPolicy::class, 'subscriber_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function validateForStatusClaimed()
    {
        if (StatusType::fromValue(StatusType::Claimed)->is($this->status)) {
            abort('422', 'Sorry, we can not allow for this status because already been claimed.');
        }
        return $this;
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
        if ($request->has('policy_id')) {

            $oldPolicies = SubscriberPolicy::where('subscriber_id', $this->id)->orderBy('id', 'DESC')->get();

            if ($oldPolicies) {
                foreach ($oldPolicies as $param) {

                    if ($param['expired_at'] <= Carbon::now()->toDateTimeString() && (string)$param['policy_id'] == (string)$request->input('policy_id')) {

                        // Renew after Expired
                        $this->updateNewPolicy($request->input(), Carbon::now());
                        break;
                    } else {

                        // Renew before Expired
                        $newDate = Carbon::parse($param['expired_at'])->addDays(1);
                        $this->updateNewPolicy($request->input(), $newDate);
                        break;
                    }
                }
            } else {
                $this->updateNewPolicy($request->input(), Carbon::now());
            }
        }

        return $this;
    }

    /**
     * @param $request
     * @param $dateTime
     * @return $this
     */
    public function updateNewPolicy($request, $dateTime) {

        $policy = Policy::firstWhere('id', $request['policy_id']);

        if ($policy) {

            $subPolicy = new SubscriberPolicy($request);

            $newDateTime = $dateTime->addMonths($policy->duration);
            $subPolicy->expired_at = $newDateTime;
            $this->subscriber_policies()->save($subPolicy);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function cacheCalculationTotalPrice()
    {
        $query = $this->subscriber_policies()->with('policy')->get();
        if ($query) {

            $this->total = $query->sum('policy.price');
            $this->save();
            $this->refresh();
        }

        return $this;
    }
}
