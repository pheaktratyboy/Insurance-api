<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use App\Service\ReportService;

class ReportController extends Controller
{
    public function countExpired() {
//        $user = auth()->user();
//        $subsciber = Subscriber::where('user_id', $user->id)->load;

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
