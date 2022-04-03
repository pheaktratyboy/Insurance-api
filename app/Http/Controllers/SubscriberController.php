<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSubscriberRequest;
use App\Http\Requests\UpdateSubscriberRequest;
use App\Http\Resources\SubscriberResource;
use App\Models\Subscriber;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class SubscriberController extends Controller
{
    public function index() {

        $subscriber = QueryBuilder::for(Subscriber::class)
            ->allowedFilters(['name_kh', 'name_en'])
            ->defaultSort('-created_at')
            ->paginate()
            ->appends(request()->query());

        return SubscriberResource::collection($subscriber);
    }

    public function getAllByOwner() {

        $user = auth()->user();
        $subscriber = QueryBuilder::for(Subscriber::class)
            ->where('user_id', $user->id)
            ->allowedFilters(['name_kh', 'name_en'])
            ->defaultSort('-created_at')
            ->paginate()
            ->appends(request()->query());

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
                ->load("subscriber_policies");

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
        });

        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Subscriber $subscriber
     * @return SubscriberResource
     */
    public function show(Subscriber $subscriber)
    {
        $subscriber->load('subscriber_policies.policy');

        return new SubscriberResource($subscriber);
    }
}
