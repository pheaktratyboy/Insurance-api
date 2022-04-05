<?php

namespace App\Models;

use App\Exceptions\HttpException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriberPolicy extends Model
{
    use HasFactory;

    protected $fillable = [
        'policy_id',
        'subscriber_id',
        'payment_method',
    ];

    protected $casts = [
        'policy_id'      => 'integer',
        'subscriber_id'  => 'integer',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @param $request
     * @return $this
     */
    public function updatePolicy($request)
    {
        $this->fill($request);
        $this->save();

        $this->subscriber->cacheCalculationTotalPrice();

        return $this;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function policy()
    {
        return $this->belongsTo(Policy::class);
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
    public function scopeJoinPolicy($query)
    {
        return $query->leftJoin('policies', 'policies.id', '=', 'subscriber_policies.policy_id');
    }

    /**
     * @param $query
     * @param $filters
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function scopeAllowFilterReport($query, $filters)
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
