<?php

namespace App\Http\Controllers;

use App\Enums\BaseRole;
use App\Enums\StatusType;
use App\Models\Claim;
use App\Models\Setting;
use App\Models\Subscriber;
use App\Models\User;
use App\Service\ReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

    public function reportAudience(Request $request)
    {
        $user = auth()->user();
        $report = new ReportService();

        if ($user->hasRole(BaseRole::Staff)) {
            $agency = User::where('disabled', 0)->with('profile')->role(BaseRole::Agency)->where('created_by', $user->id);
            $userId = collect($agency->get())->pluck('id');
            $userId[] = $user->id;

            $subscribers  = $report->querySubscriberByAudience($userId, $request);
        } elseif ($user->hasRole(BaseRole::Agency)) {
            $subscribers  = $report->querySubscriberByAudience([$user->id], $request);
        } else {
            $subscribers  = $report->querySubscriberByAudience(null, $request);
        }

        $collection = collect($subscribers)->groupBy('created_date')->map(function ($item, $key) {
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

    public function reportYearly(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
        ]);

        $user = User::firstWhere('id', $request->input('user_id'));

        if ($user) {

            $report = new ReportService();

            if ($user->hasRole(BaseRole::Staff)) {
                $agency = User::where('disabled', 0)->with('profile')->role(BaseRole::Agency)->where('created_by', $user->id);
                $userId = collect($agency->get())->pluck('id');
                $userId[] = $user->id;

                $subscribers  = $report->querySubscriberByYearly($userId);

            } elseif ($user->hasRole(BaseRole::Agency)) {
                $subscribers  = $report->querySubscriberByYearly([$user->id]);

            } else {
                $subscribers  = $report->querySubscriberByYearly(null);
            }

            $collection = collect($subscribers)->groupBy('created_date')->map(function ($item, $key) {
                $totalSell = floatval($item->sum('policy_price'));
                $totalSubscriber = collect($item)->groupBy('subscriber_id')->count();
                $totalClaim = collect($item)->where('status', StatusType::Claimed)->groupBy('subscriber_id')->count();

                $countExpired = collect($item)->where('expired_at', '<=', Carbon::now()->toDateTimeString())->groupBy('subscriber_id')->count();

                $newData["total_subscriber"] = $totalSubscriber;
                $newData["total_expired"] = $countExpired;
                $newData["total_amount"] = $totalSell;
                $newData["total_claim"] = $totalClaim;

                return $newData;
            });

            return response()->json([
                'data' => $collection
            ]);
        }
    }

    public function reportMonthlyTransaction(Request $request)
    {
        if (!$request->has('from_date') && !$request->has('to_date')) {
            $convertedFromDate = Carbon::now()->startOfYear()->format('Y-m-d');
            $convertedToDate   = Carbon::now()->format('Y-m-d-H:m:s');
        }

        if ($request->has('from_date') && $request->has('to_date')) {

            $fromDate = Carbon::parse($request->get('from_date'));
            $toDate   = Carbon::parse($request->get('to_date'));

            $convertedFromDate = $fromDate->format('Y-m-d');
            $convertedToDate   = $toDate->format('Y-m-d');
        }

        $user = auth()->user();

        if ($user->hasRole([BaseRole::Admin, BaseRole::Master])) {

            $subscribers = Subscriber::whereBetween('created_at', [$convertedFromDate,$convertedToDate])->get();

        } elseif ($user->hasRole(BaseRole::Staff)) {

            $agencyId = User::where('created_by', $user->id)->get();
            $all = collect($agencyId)->pluck('id')->push($user->id);

            $subscribers = Subscriber::whereIn('user_id', $all)->whereBetween('created_at', [$convertedFromDate,$convertedToDate])->get();

        } elseif ($user->hasRole(BaseRole::Agency)) {
            $subscribers = Subscriber::where('user_id', $user->id)->whereBetween('created_at', [$convertedFromDate,$convertedToDate])->get();
        }

        if ($subscribers) {
            $collection = collect($subscribers);
            $resultGroupBy = $collection->groupBy(function ($item, $key) {
                return Carbon::parse($item['created_at'])->format('M-Y');
            });

            return response()->json([
                'data' => $resultGroupBy
            ]);
        }
    }

    public function reportTopAgency(Request $request)
    {
        $user = auth()->user();

        if (!$request->has('from_date') && !$request->has('to_date')) {
            $convertedFromDate = Carbon::now()->startOfMonth()->format('Y-m-d');
            $convertedToDate   = Carbon::now()->addDay()->format('Y-m-d');
        }

        if ($request->has('from_date') && $request->has('to_date')) {

            $fromDate = Carbon::parse($request->get('from_date'));
            $toDate   = Carbon::parse($request->get('to_date'));

            $convertedFromDate = $fromDate->format('Y-m-d');
            $convertedToDate   = $toDate->format('Y-m-d');
        }

        if ($user->hasRole(BaseRole::Staff)) {

            $agency = User::where('disabled', 0)->with('profile')->role(BaseRole::Agency)->where('created_by', $user->id)->get();
            $agencyId = collect($agency)->pluck('id');

            if ($agencyId) {

                $collection = collect($agency)->map(function ($item, $key) use ($convertedFromDate, $convertedToDate) {

                    $subscribers = Subscriber::where('user_id', $item->id)->whereBetween('created_at', [$convertedFromDate,$convertedToDate])->get();
                    $collection = collect($subscribers);
                    $resultCount = $collection->groupBy("id")->count();

                    $member["member"] = $resultCount;
                    $member["user_name"] = $item->full_name;

                    return $member;
                });

                return response()->json([
                    'data' => $collection
                ]);
            }
        }

        if ($user->hasRole([BaseRole::Admin, BaseRole::Master])) {

            $agency = User::where('disabled', 0)->with('profile')->role(BaseRole::Agency)->get();
            $agencyId = collect($agency)->pluck('id');

            if ($agencyId) {

                $collection = collect($agency)->map(function ($item, $key) {

                    $subscribers = Subscriber::where('user_id', $item->id)->get();
                    $collection = collect($subscribers);
                    $resultCount = $collection->groupBy("id")->count();

                    $member["member"] = $resultCount;
                    $member["user_name"] = $item->full_name;

                    return $member;
                });

                return response()->json([
                    'data' => $collection
                ]);
            }
        }
    }

    public function reportDashboard(): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user();
        $getReminder = Setting::Reminder()->option;
        $report      = new ReportService();

        $countExpired = 0;
        $totalSell = 0;
        $totalSubscriber = 0;
        $countBeforeExpired = 0;
        $countTotalClaimed = 0;

        $claimed = Claim::where('status', StatusType::Approved)->count();
        if ($claimed) {
            $countTotalClaimed = $claimed;
        }

        if ($user->hasRole(BaseRole::Staff)) {
            $agency = User::where('disabled', 0)->with('profile')->role(BaseRole::Agency)->where('created_by', $user->id);

            $countAgency = $agency->count();
            $userId = collect($agency->get())->pluck('id');
            $userId[] = $user->id;


            if (!empty($userId)) {
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
                    'total_claimed'                 => $countTotalClaimed,
                ],
            ]);
        } elseif ($user->hasRole(BaseRole::Agency)) {
            if (!empty($user->id)) {
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
                    'total_claimed'                 => $countTotalClaimed,
                ],
            ]);
        } else {
            $employee = User::where('disabled', 0)->with('profile')->get();
            $countStaff = User::where('disabled', 0)->with('profile')->role(BaseRole::Staff)->count();
            $countAgency = User::where('disabled', 0)->with('profile')->role(BaseRole::Agency)->count();

            $userId = collect($employee)->pluck('id');

            if (!empty($userId)) {
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
                    'total_claimed'                 => $countTotalClaimed,
                ],
            ]);
        }
    }
}
