<?php

namespace App\Http\Controllers;

use App\Http\Resources\TrackingHistoryResource;
use App\Models\TrackingHistory;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;


class TrackingHistoryController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getAll(Request $request) {

        $request->validate([
            'reference_id' => 'required|max:10',
        ]);

        $history = TrackingHistory::with('user')
            ->with('user')
            ->where('reference_id', $request->input('reference_id'))
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();

        return TrackingHistoryResource::collection($history);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request) {

        $request->validate([
            'reference_id' => 'required|max:10',
        ]);

        $queryBuilder = QueryBuilder::for(TrackingHistory::class)
            ->with('user')
            ->where('reference_id', $request->input('reference_id'))
            ->defaultSort('-created_at')
            ->paginate()
            ->appends(request()->query());

        return TrackingHistoryResource::collection($queryBuilder);
    }

    /**
     * @param Request $request
     * @return TrackingHistoryResource
     */
    public function details(Request $request)
    {
        $request->validate([
            'reference_id' => 'required|max:10',
        ]);

        $history = TrackingHistory::with('user')
            ->firstWhere('reference_id', $request->input('reference_id'))
            ->load('user');

        return new TrackingHistoryResource($history);
    }
}
