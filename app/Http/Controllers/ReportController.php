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
    public function reportSubscriber()
    {
        $report         = new ReportService();
        $collection     = collect($report->getSubscribers());
        $user = auth()->user();

        if ($user->hasRole([BaseRole::Staff, BaseRole::Agency])) {
            $subscribers = $collection->where('user_id', $user->id)->all();
        } else {
            $subscribers = $collection->all();
        }

        return response()->json([
            'data' => [
                'subscribers' => $subscribers,
            ],
        ]);
    }

    public function reportYearly()
    {
        $report       = new ReportService();
        $subscribers  = $report->querySubscriberPolicies();

        $collection   = collect($subscribers)->groupBy('created_date')->map(function ($item, $key) {

            $totalSell = floatval($item->sum('policy_price'));
            $totalSubscriber = collect($item)->groupBy('subscriber_id')->count();
            $countExpired = collect($item)->where('expired_at', '<=', Carbon::now()->toDateTimeString())->groupBy('subscriber_id')->count();

            $newData["total_subscriber"] = $totalSubscriber;
            $newData["total_expired"] = $countExpired;
            $newData["total_amount"] = $totalSell;

            return $newData;
        });

        return response()->json([
            'data' => $collection
        ]);
    }

    public function reportDashboard()
    {
        $user = auth()->user();
        $getReminder = Setting::Reminder()->option;

        $countExpired = 0;
        $countBeforeExpired = 0;
        $totalSell = 0;
        $totalSubscriber = 0;

        if ($user->hasRole(BaseRole::Staff)) {
            $agency = User::where('disabled', 0)->with('profile')->role(BaseRole::Agency)->where('created_by', $user->id);

            $countAgency = $agency->count();
            $userId = collect($agency->get())->pluck('id');
            $userId[] = $user->id;


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
                    'total_expired'                 => $countExpired,
                    'total_before_expiring'         => $countBeforeExpired,
                ],
            ]);
        } elseif ($user->hasRole(BaseRole::Agency)) {
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
                    'total_expired'                 => $countExpired,
                    'total_before_expiring'         => $countBeforeExpired,
                ],
            ]);
        } else {
            $employee = User::where('disabled', 0)->with('profile')->role([BaseRole::Agency, BaseRole::Staff, BaseRole::Admin])->get();
            $countStaff = User::where('disabled', 0)->with('profile')->role(BaseRole::Staff)->count();
            $countAgency = User::where('disabled', 0)->with('profile')->role(BaseRole::Agency)->count();

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
                    'total_expired'                 => $countExpired,
                    'total_before_expiring'         => $countBeforeExpired,
                ],
            ]);
        }
    }
}
