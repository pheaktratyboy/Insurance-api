<?php

namespace App\Http\Controllers;

use App\Enums\BaseRole;
use App\Enums\TrackingType;
use App\Http\Requests\CreateSubscriberRequest;
use App\Http\Requests\UpdateSubscriberRequest;
use App\Http\Resources\SubscriberResource;
use App\Models\Subscriber;
use App\Models\TrackingHistory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class SubscriberController extends Controller
{
    public function index() {

        $user = auth()->user();

        $subscriber = QueryBuilder::for(Subscriber::class)
            ->allowedFilters(['name_kh', 'name_en'])
            ->defaultSort('-created_at')
            ->paginate()
            ->appends(request()->query());

        if ($user->hasRole([BaseRole::Staff, BaseRole::Agency])) {

            $subscriber = QueryBuilder::for(Subscriber::class)
                ->where('user_id', $user->id)
                ->allowedFilters(['name_kh', 'name_en'])
                ->defaultSort('-created_at')
                ->paginate()
                ->appends(request()->query());
        }

        return SubscriberResource::collection($subscriber);
    }

    /**
     * @param CreateSubscriberRequest $request
     * @return SubscriberResource
     */
    public function store(CreateSubscriberRequest $request) {

        $result = DB::transaction(function () use ($request) {
            $subscriber = new Subscriber;
            $subscriber->createNewSubscriber($request)
                ->addSubscriberPolicy($request)
                ->cacheCalculationTotalPrice()
                ->load(['company','subscriber_policies.policy']);

            /**
             * Tracking History
             */
            TrackingHistory::createSubscriberTracking($subscriber, TrackingType::Created);

            return $subscriber;
        });

        return new SubscriberResource($result);
    }

    /**
     * @param UpdateSubscriberRequest $request
     * @param Subscriber $subscriber
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function update(UpdateSubscriberRequest $request, Subscriber $subscriber) {

        $subscriber->validateForStatusClaimed();

        DB::transaction(function () use ($request, $subscriber) {
            $subscriber->update($request->validated());
            $subscriber->load(['company','subscriber_policies.policy']);

            /**
             * Tracking History
             */
            TrackingHistory::createSubscriberTracking($subscriber, TrackingType::Updated);
        });

        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Subscriber $subscriber
     * @return SubscriberResource
     */
    public function show(Subscriber $subscriber)
    {
        $subscriber->load(['company','subscriber_policies.policy']);

        return new SubscriberResource($subscriber);
    }
}
