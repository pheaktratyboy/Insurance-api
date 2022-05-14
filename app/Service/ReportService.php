<?php


namespace App\Service;

use App\Enums\BaseRole;
use App\Models\SubscriberPolicy;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function querySubscriberByAudience($userId, $request): \Illuminate\Support\Collection
    {
        /** if request doesn't has filter then automatically filter only three months */
        if (!$request->has('from_date') && !$request->has('to_date')) {
            $defaultToDate     = Carbon::now()->startOfDay();

            $convertedToDate   = $defaultToDate->addDay()->format('Y-m-d');
            $convertedFromDate = $defaultToDate->subMonths(3)->format('Y-m-d');
        }

        /** filter From Date and To Date*/
        if ($request->has('from_date') && $request->has('to_date')) {
            $fromDate = Carbon::parse($request->get('from_date'));
            $toDate   = Carbon::parse($request->get('to_date'));

            $convertedFromDate = $fromDate->format('Y-m-d');
            $convertedToDate   = $toDate->format('Y-m-d');
        }

        /** filter from date only when to date is not provided */
        if ($request->has('from_date') && !$request->has('to_date')) {
            $fromDate          = Carbon::parse($request->get('from_date'));
            $toDate            = $fromDate->addMonths(3);

            $convertedFromDate = $fromDate->format('Y-m-d');
            $convertedToDate   = $toDate->format('Y-m-d');
        }

        /** filter only to date */
        if (!$request->has('from_date') && $request->has('to_date')) {
            $toDate            = Carbon::parse($request->get('to_date'));
            $fromDate          = $toDate->subMonths(3);

            $convertedFromDate = $fromDate->format('Y-m-d');
            $convertedToDate   = $toDate->format('Y-m-d');
        }

        $filter = "%Y-%m-%d";
        if ($request->has('year')) {
            $filter = "%Y-%m";
        }

        if ($userId) {
            return DB::table('subscriber_policies')
                ->join('subscribers', function ($join) use ($userId) {
                    $join->on('subscribers.id', '=', 'subscriber_policies.subscriber_id')->whereIn('user_id', $userId);
                })
                ->leftJoin('policies', 'policies.id', '=', 'subscriber_policies.policy_id')
                ->select(
                    'subscribers.user_id',
                    'subscribers.created_at',
                    DB::raw("DATE_FORMAT(subscribers.created_at, '$filter') as created_date"),
                    'subscriber_policies.expired_at',
                    'subscriber_policies.subscriber_id',
                    'policies.price as policy_price',
                )
                ->whereBetween('subscribers.created_at', [$convertedFromDate,$convertedToDate])
                ->get();
        } else {
            return DB::table('subscriber_policies')
                ->join('subscribers', function ($join) {
                    $join->on('subscribers.id', '=', 'subscriber_policies.subscriber_id');
                })
                ->leftJoin('policies', 'policies.id', '=', 'subscriber_policies.policy_id')
                ->select(
                    'subscribers.user_id',
                    'subscribers.created_at',
                    DB::raw("DATE_FORMAT(subscribers.created_at, '$filter') as created_date"),
                    'subscriber_policies.expired_at',
                    'subscriber_policies.subscriber_id',
                    'policies.price as policy_price',
                )
                ->whereBetween('subscribers.created_at', [$convertedFromDate,$convertedToDate])
                ->get();
        }
    }

    public function querySubscriberByYearly($userId): \Illuminate\Support\Collection
    {

        $startOfYear = Carbon::now()->startOfYear()->format('Y-m-d');
        $endOfYear = Carbon::now()->endOfYear()->format('Y-m-d');

        if ($userId) {
            return DB::table('subscriber_policies')
                ->join('subscribers', function ($join) use ($userId) {
                    $join->on('subscribers.id', '=', 'subscriber_policies.subscriber_id')->whereIn('user_id', $userId);
                })
                ->leftJoin('policies', 'policies.id', '=', 'subscriber_policies.policy_id')
                ->select(
                    'subscribers.user_id',
                    'subscribers.status',
                    'subscribers.created_at',
                    DB::raw("DATE_FORMAT(subscribers.created_at, '%Y-%m') as created_date"),
                    'subscriber_policies.expired_at',
                    'subscriber_policies.subscriber_id',
                    'policies.price as policy_price',
                )
                ->whereBetween('subscribers.created_at', [$startOfYear,$endOfYear])
                ->get();
        } else {
            return DB::table('subscriber_policies')
                ->join('subscribers', function ($join) {
                    $join->on('subscribers.id', '=', 'subscriber_policies.subscriber_id');
                })
                ->leftJoin('policies', 'policies.id', '=', 'subscriber_policies.policy_id')
                ->select(
                    'subscribers.user_id',
                    'subscribers.status',
                    'subscribers.created_at',
                    DB::raw("DATE_FORMAT(subscribers.created_at, '%Y-%m') as created_date"),
                    'subscriber_policies.expired_at',
                    'subscriber_policies.subscriber_id',
                    'policies.price as policy_price',
                )
                ->whereBetween('subscribers.created_at', [$startOfYear,$endOfYear])
                ->get();
        }
    }

    public function querySubscriberPoliciesByUserId($userId): \Illuminate\Support\Collection
    {
        return DB::table('subscriber_policies')
            ->join('subscribers', function ($join) use ($userId) {
                $join->on('subscribers.id', '=', 'subscriber_policies.subscriber_id')
                    ->whereIn('user_id', $userId);
            })
            ->leftJoin('policies', 'policies.id', '=', 'subscriber_policies.policy_id')
            ->select(
                'subscribers.user_id',
                'subscriber_policies.expired_at',
                'subscriber_policies.subscriber_id',
                'policies.price as policy_price',
            )
            ->get();
    }

    public function getSubscribers()
    {
        return SubscriberPolicy::join('subscribers', function ($join) {
            $join->on('subscribers.id', '=', 'subscriber_policies.subscriber_id');
        })
            ->JoinPolicy()
            ->JoinCompany()
            ->select(
                'subscribers.name_kh',
                'subscribers.name_en',
                'subscribers.identity_number',
                'subscribers.date_of_birth',
                'subscribers.phone_number',
                'subscribers.address',
                'subscribers.place_of_birth',
                'subscribers.gender',
                'subscribers.religion',
                'subscribers.created_at as subscriber_date',
                'subscribers.status',
                'subscribers.user_id',
                'companies.name as company_name',
                'subscriber_policies.policy_id',
                'subscriber_policies.payment_method',
                'subscriber_policies.expired_at',
                'policies.name as policy_name',
            )
            ->allowFilterReport([
                'date',
                'from_date',
                'to_date',
                'expired_at',
                'company_id',
                'status'
            ])
            ->get();
    }

    public function exportSubscribers()
    {
        $result = collect($this->getSubscribers());
        $user = auth()->user();

        if ($user->hasRole([BaseRole::Staff, BaseRole::Agency])) {
            return $result->where('user_id', $user->id)->all();
        } else {
            return $result->all();
        }
    }

    public function exportSubscriberDetails()
    {
        return;
    }
}
