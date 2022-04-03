<?php


namespace App\Service;

use App\Models\Subscriber;

class ReportService
{

    public function getSubscribers()
    {
        $user = auth()->user();

        return Subscriber::join('subscriber_policies', 'subscriber_policies.id', 'subscriber_policies.subscriber_id')
//            ->leftJoin('policies', 'policies.id', '=', 'subscriber_policies.policy_id')

//            ->select(
//                'subscribers.name_kh',
//                'subscribers.name_en',
//                'subscribers.identity_number',
//                'subscribers.date_of_birth',
////                'subscribers.phone_number',
////                'subscribers.address',
////                'subscribers.place_of_birth',
////                'subscribers.gender',
////                'subscribers.religion',
////                'subscribers.created_at as subscriber_date',
////                'subscribers.status',
////                'companies.name as company_name',
//
////                'subscriber_policies.policy_id',
////                'subscriber_policies.payment_method',
////                'subscriber_policies.expired_at',
//
////                'policies.name as policy_name',
//            )
//            ->where('subscriber_policies.expired_at', '2022-04-02 11:00:16')
//            ->where('subscribers.status', 'approved')
//            ->where('subscribers.user_id', $user->id)
//            ->allowFilterReportSubscriber(['date','from_date','to_date','company_id'])
            ->get();
    }
}
