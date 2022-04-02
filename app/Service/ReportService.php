<?php


namespace App\Service;

use App\Models\Subscriber;

class ReportService
{
    public function getSubscribers()
    {
        return Subscriber::join('subscriber_policies', 'subscriber_policies.id', '=', 'subscriber_policies.subscriber_id')
            ->scopeJoinCompany()
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

                'companies.name as company_name',

                'subscribers.created_at as subscriber_date',
                'subscribers.status'
            )
            ->get();
    }
}
