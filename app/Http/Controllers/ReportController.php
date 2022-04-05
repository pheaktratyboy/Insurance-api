<?php

namespace App\Http\Controllers;

use App\Enums\BaseRole;
use App\Models\Subscriber;
use App\Models\SubscriberPolicy;
use App\Models\User;
use App\Service\ReportService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function countExpired() {

        //$user = auth()->user();
        //$subsciber = Subscriber::where('user_id', $user->id)->load;


//                $subscriber = Subscriber::whereIn('user_id', $userId)->with(['subscriber_policies' => function($q) {
//                    $q->where('expired_at', '>=' , Carbon::now()->toDateTimeString());
//                }]);

//                $subPolicies = SubscriberPolicy::with(['subscriber' => function($q) use ($userId) {
//                    $q->whereIn('user_id', [3]);
//                }])->get();
//
//                echo  json_encode($subPolicies);
//                echo  json_encode($subscriber);
//                echo json_encode(collect($subscriber->get()));


//        $sub = $subscriber->join('subscriber_policies', 'subscriber_policies.id', 'subscriber_policies.subscriber_id');
        //$totalSell = floatval($subscriber->sum('total'));
        //$sub = $subscriber->with('subscriber_policies')->where('subscriber_policies.expired_at', '2024-04-04 07:03:22');
    }


    public function reportDashboard() {

        $user = auth()->user();

        if ($user->hasRole(BaseRole::Staff)) {

            $totalAgency = User::with('profile')->role(BaseRole::Agency)->where('created_by', $user->id)->get();
            $userId = collect($totalAgency)->pluck('id');

            $countExpired = 0;
            $totalSell = 0;
            $totalSubscriber = 0;

            if (!empty($userId)) {
                $report       = new ReportService();
                $subscribers  = $report->querySubscriberPoliciesByUserId($userId);
                $collection   = collect($subscribers);

                //Count Total Subscriber
                $totalSubscriber = $collection->groupBy('subscriber_id')->count();

                //Sub Total Sell
                $totalSell = floatval($subscribers->sum('policy_price'));

                //Count User Expired
                $countExpired = $collection->where('expired_at', '<=', Carbon::now()->toDateTimeString())->groupBy('subscriber_id')->count();
            }

            return response()->json([
                'data' => [
                    'total_subscriber'              => $totalSubscriber,
                    'total_sell'                    => $totalSell,
                    'total_agency'                  => 0,
                    'total expired'                 => $countExpired,
                    'total_before_expiring'         => 0,
                ],
            ]);

        } else if ($user->hasRole(BaseRole::Agency)) {

            $user = auth()->user();
            $countExpired = 0;
            $totalSell = 0;
            $totalSubscriber = 0;

            if (!empty($user->id)) {

                $report       = new ReportService();
                $subscribers  = $report->querySubscriberPoliciesByUserId([$user->id]);
                $collection   = collect($subscribers);

                //Count Total Subscriber
                $totalSubscriber = $collection->groupBy('subscriber_id')->count();

                //Sub Total Sell
                $totalSell = floatval($subscribers->sum('policy_price'));

                //Count User Expired
                $countExpired = $collection->where('expired_at', '<=', Carbon::now()->toDateTimeString())->groupBy('subscriber_id')->count();
            }

            return response()->json([
                'data' => [
                    'total_subscriber'              => $totalSubscriber,
                    'total_sell'                    => $totalSell,
                    'total expired'                 => $countExpired,
                    'total_before_expiring'         => 0,
                ],
            ]);

        } else {

            $employee = User::with('profile')->role([BaseRole::Agency, BaseRole::Staff, BaseRole::Admin])->get();
            $userId = collect($employee)->pluck('id');

            $countExpired = 0;
            $totalSell = 0;
            $totalSubscriber = 0;

            if (!empty($userId)) {

                $report       = new ReportService();
                $subscribers  = $report->querySubscriberPoliciesByUserId($userId);
                $collection   = collect($subscribers);


                //echo json_encode($subscribers);
                echo json_encode(Carbon::today()->toDateTimeString());
                echo json_encode(Carbon::today()->addMonths(1)->toDateTimeString());

                $countBeforeExpired = $collection->whereBetween('expired_at', [Carbon::today()->toDateTimeString(), Carbon::today()->addMonths(1)->toDateTimeString()]);

                //Carbon::today()->toDateTimeString()
                echo json_encode($countBeforeExpired);
                dd();

                //Count Total Subscriber
                $totalSubscriber = $collection->groupBy('subscriber_id')->count();

                //Sub Total Sell
                $totalSell = floatval($subscribers->sum('policy_price'));

                //Count User Expired
                $countExpired = $collection->where('expired_at', '<=', Carbon::now()->toDateTimeString())->groupBy('subscriber_id')->count();
            }

            return response()->json([
                'data' => [
                    'total_subscriber'              => $totalSubscriber,
                    'total_sell'                    => $totalSell,
                    'total_staff'                   => 0,
                    'total_agency'                  => 0,
                    'total expired'                 => $countExpired,
                    'total_before_expiring'         => 0,
                ],
            ]);
        }
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
