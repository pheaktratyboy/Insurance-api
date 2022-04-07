<?php

namespace App\Http\Controllers;

use App\Enums\BaseRole;
use App\Models\Setting;
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
        $getReminder = Setting::Reminder()->option;

        $countExpired = 0;
        $countBeforeExpired = 0;
        $totalSell = 0;
        $totalSubscriber = 0;

        if ($user->hasRole(BaseRole::Staff)) {

            $query = User::where('disabled', 0)->with('profile')->where('created_by', $user->id);

            $countAgency = $query->where('created_by', $user->id)->count();
            $userId = collect($query->get())->pluck('id');



            if (!empty($userId)) {
                $report       = new ReportService();
                $subscribers  = $report->querySubscriberPoliciesByUserId($userId);
                $collection   = collect($subscribers);

                //Count Before Total Expired
                $countBeforeExpired = $collection->whereBetween('expired_at', [Carbon::today()->toDateTimeString(), Carbon::today()->addMonths($getReminder)->toDateTimeString()])->count();

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
                    'total_agency'                  => $countAgency,
                    'total expired'                 => $countExpired,
                    'total_before_expiring'         => $countBeforeExpired,
                ],
            ]);

        } else if ($user->hasRole(BaseRole::Agency)) {


            if (!empty($user->id)) {

                $report       = new ReportService();
                $subscribers  = $report->querySubscriberPoliciesByUserId([$user->id]);
                $collection   = collect($subscribers);

                //Count Before Total Expired
                $countBeforeExpired = $collection->whereBetween('expired_at', [Carbon::today()->toDateTimeString(), Carbon::today()->addMonths($getReminder)->toDateTimeString()])->count();

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
                    'total_before_expiring'         => $countBeforeExpired,
                ],
            ]);

        } else {

            $query = User::where('disabled', 0)->with('profile');
            $employee = $query->role([BaseRole::Agency, BaseRole::Staff, BaseRole::Admin])->get();
            $countStaff = $query->role([BaseRole::Staff])->count();
            $countAgency = $query->role([BaseRole::Agency])->count();
            $userId = collect($employee)->pluck('id');


            if (!empty($userId)) {

                $report       = new ReportService();
                $subscribers  = $report->querySubscriberPoliciesByUserId($userId);
                $collection   = collect($subscribers);

                //Count Before Total Expired
                $countBeforeExpired = $collection->whereBetween('expired_at', [Carbon::today()->toDateTimeString(), Carbon::today()->addMonths($getReminder)->toDateTimeString()])->count();

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
                    'total_staff'                   => $countStaff,
                    'total_agency'                  => $countAgency,
                    'total expired'                 => $countExpired,
                    'total_before_expiring'         => $countBeforeExpired,
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
