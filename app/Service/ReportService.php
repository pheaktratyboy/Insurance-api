<?php


namespace App\Service;

use App\Enums\BaseRole;
use App\Models\SubscriberPolicy;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function querySubscriberPolicies(): \Illuminate\Support\Collection
    {
        return DB::table('subscriber_policies')
            ->join('subscribers', function ($join) {
                $join->on('subscribers.id', '=', 'subscriber_policies.subscriber_id');
            })
            ->leftJoin('policies', 'policies.id', '=', 'subscriber_policies.policy_id')
            ->select(
                'subscribers.user_id',
                'subscribers.created_at',
                'subscriber_policies.expired_at',
                'subscriber_policies.subscriber_id',
                'policies.price as policy_price',
            )
            ->get();
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
