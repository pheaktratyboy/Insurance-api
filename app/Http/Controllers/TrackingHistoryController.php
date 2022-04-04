<?php

namespace App\Http\Controllers;

use App\Http\Resources\TrackingHistoryResource;
use App\Models\TrackingHistory;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;


class TrackingHistoryController extends Controller
{
    public function getAll(Request $request) {

        $history = TrackingHistory::with('user')
            ->where('reference_id', $request->input('reference_id'))
            ->get();

        return TrackingHistoryResource::collection($history);
    }

    public function index(Request $request) {

        $queryBuilder = QueryBuilder::for(TrackingHistory::class)
            ->with('user')
            ->where('reference_id', $request->input('reference_id'))
            ->defaultSort('-created_at')
            ->paginate()
            ->appends(request()->query());

        return TrackingHistoryResource::collection($queryBuilder);
    }

    /**
     * @param TrackingHistory $history
     * @return TrackingHistoryResource
     */
    public function show(TrackingHistory $history)
    {
        echo json_encode($history);
        return new TrackingHistoryResource($history);
    }
}
