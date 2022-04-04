<?php

namespace App\Http\Controllers;

use App\Http\Resources\TrackingHistoryResource;
use App\Models\TrackingHistory;


class TrackingHistoryController extends Controller
{
    public function index() {

    }

    /**
     * @param TrackingHistory $history
     * @return TrackingHistoryResource
     */
    public function show(TrackingHistory $history) {
        return new TrackingHistoryResource($history);
    }
}
