<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
}
