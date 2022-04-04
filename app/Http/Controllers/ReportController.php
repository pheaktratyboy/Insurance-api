<?php

namespace App\Http\Controllers;

use App\Enums\BaseRole;
use App\Models\Subscriber;
use App\Models\User;
use App\Service\ReportService;

class ReportController extends Controller
{
    public function countExpired() {

        //$user = auth()->user();
        //$subsciber = Subscriber::where('user_id', $user->id)->load;
    }

    public function reportDashboard() {
        $user = auth()->user();
//        $report         = new ReportService();
//        $subscribers    = $report->getCommission();

        //$subscriber = User::with('profile')->role(BaseRole::Agency)->where('created_by', $user->id)->count();
        $totalAgency = User::with('profile')->role(BaseRole::Agency)->where('created_by', $user->id)->count();

        return response()->json([
            'data' => [
                'total_subscriber'                  => 0, // sub
                'total_sell'                        => 0, // sub
                'total_agency'                      => $totalAgency,
                'total_expiring_subscriber'         => 0, // sub
            ],
        ]);
    }

    public function countSubscriber() {
        $user = auth()->user();
        $subscriber = Subscriber::where('user_id', $user->id)
            ->with('subscriber_policies')
            ->get();

        echo json_encode($subscriber);
    }

    public function reportSubscriber()
    {
        $report         = new ReportService();
        $subscribers    = $report->getSubscribers();

        return response()->json([
            'data' => [
                'subscribers' => $subscribers,
            ],
        ]);
    }
}
