<?php

namespace App\Models;

use App\Enums\StatusType;
use App\Exceptions\HttpException;
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
    ];

    protected $casts = [
        'user_id'               => 'integer',
        'company_id'            => 'integer',
        'avatar'                => 'array',
        'id_or_passport_front'  => 'array',
        'id_or_passport_back'   => 'array',
        'attachments'           => 'array',
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
        return $this->hasMany(SubscriberPolicy::class, 'subscriber_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'id');
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
        if (!is_a($this->status, StatusType::Claimed)) {
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

    /**
     * @param $query
     * @return mixed
     */
    public function scopeJoinCompany($query)
    {
        return $query->leftJoin('companies', 'companies.id', '=', 'subscribers.company_id');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeJoinSubPolicies($query)
    {
        return $query->join('subscriber_policies', 'subscriber_policies.id', '=', 'subscriber_policies.subscriber_id');
    }

    public function scopeJoinConsigneeLocation($query)
    {
        return $query->join(DB::raw("
        (select
            subscribers.name_kh,
            subscribers.name_en,
            subscriber_policies.subscriber_id,
            subscriber_policies.policy_id,
            subscriber_policies.payment_method,
            subscribers.date_of_birth,
            subscribers.identity_number,
            subscribers.religion,
            subscribers.gender,
            subscribers.place_of_birth,
            subscribers.address,
            subscribers.phone_number
            )
        from
            subscribers
            INNER JOIN
            subscriber_policies
            on
            subscribers.id = subscriber_policies.subscriber_id
        where
	    subscribers.`status` = 'approved'
	    "), function ($join) {
            $join->on('consigneeLocation.id', '=', 'orders.consignee_location_id');
        });
    }

    /**
     * @param $query
     * @param $filters
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function scopeAllowFilterReportSubscriber($query, $filters)
    {
        $request = request();

        /** if request doesn't has filter then automatically filter only three months */
        if (!$request->has('filter')) {
            $defaultToDate     = Carbon::now();
            $convertedToDate   = $defaultToDate->format('Y-m-d');
            $defaultFromDate   = $defaultToDate->subMonths(3);
            $convertedFromDate = $defaultFromDate->format('Y-m-d');
            return $query->whereBetween('subscribers.created_at', [$convertedFromDate,$convertedToDate]);
        }

        /** get all filters */
        $filter = collect($request->get('filter'));

        /** check allow filter */
        $notAllowFilters = $filter->keys()->diff($filters);
        if ($notAllowFilters->isNotEmpty()) {
            $strNotAllowFilter = collect($notAllowFilters)->implode(', ');
            throw new HttpException('400', " This fields {$strNotAllowFilter} are not allow for filtering ", '');
        }

        /** filter by courier_id */
        if ($filter->has('company_id')) {
            $query->where('subscribers.company_id', $filter->get('company_id'));
        }

        /** filter from date and to date*/
        if ($filter->has('from_date') && $filter->has('to_date')) {
            $fromDate = Carbon::parse($filter->get('from_date'));
            $toDate   = Carbon::parse($filter->get('to_date'));

            /** allow only diff day <= 90 day*/
            $diffDays = $fromDate->diff($toDate)->format('%a');
            if ($diffDays <= 90) {
                $convertedFromDate = $fromDate->format('Y-m-d');
                $convertedToDate   = $toDate->format('Y-m-d');
                $query->whereBetween('subscribers.created_at', [$convertedFromDate,$convertedToDate]);
            } else {
                throw new HttpException('400', 'This range is more than 90 day', 'not allow');
            }
        }

        /** filter from date only when to date is not provided */
        if ($filter->has('from_date') && !$filter->has('to_date')) {
            $fromDate          = Carbon::parse($filter->get('from_date'));
            $toDate            = $fromDate->addMonths(3);
            $convertedFromDate = $fromDate->format('Y-m-d');
            $convertedToDate   = $toDate->format('Y-m-d');
            $query->whereBetween('subscribers.created_at', [$convertedFromDate,$convertedToDate]);
        }

        /** filter only to date */
        if (!$filter->has('from_date') && $filter->has('to_date')) {
            $toDate            = Carbon::parse($filter->get('to_date'));
            $fromDate          = $toDate->subMonths(3);
            $convertedFromDate = $fromDate->format('Y-m-d');
            $convertedToDate   = $toDate->format('Y-m-d');
            $query->whereBetween('subscribers.created_at', [$convertedFromDate,$convertedToDate]);
        }

        /** check for mobile */
        if ($filter->has('date')) {
            $date = Carbon::parse($filter->get('date'));
            $query->whereMonth('subscribers.created_at', $date->format('m'))
                ->whereYear('subscribers.created_at', $date->format('Y'));
        }

        return $query;
    }
}
