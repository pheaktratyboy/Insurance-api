<?php

namespace App\Http\Controllers;


use App\Http\Requests\CreateSubscriberPolicyRequest;
use App\Http\Requests\UpdateSubscriberPolicyRequest;
use App\Http\Resources\SubscriberPolicyResource;
use App\Http\Resources\SubscriberResource;
use App\Models\Subscriber;
use App\Models\SubscriberPolicy;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class SubscriberPolicyController extends Controller
{

    /**
     * @param CreateSubscriberPolicyRequest $request
     * @param Subscriber $subscriber
     * @return SubscriberResource
     */
    public function store(CreateSubscriberPolicyRequest $request, Subscriber $subscriber) {

        $subscriber->validateForStatusClaimed();

        $result = DB::transaction(function () use ($request, $subscriber) {
            return $subscriber->addSubscriberPolicy($request)
                ->cacheCalculationTotalPrice()
                ->load('subscriber_policies');
        });

        return new SubscriberResource($result);
    }

    /**
     * @param UpdateSubscriberPolicyRequest $request
     * @param SubscriberPolicy $subscriber_policy
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function update(UpdateSubscriberPolicyRequest $request, SubscriberPolicy $subscriber_policy) {

        DB::transaction(function () use ($request, $subscriber_policy) {
            $subscriber_policy->updatePolicy($request->validated());
        });

        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param SubscriberPolicy $subscriberPolicy
     * @return SubscriberPolicyResource
     */
    public function show(SubscriberPolicy $subscriberPolicy)
    {
        return new SubscriberPolicyResource($subscriberPolicy->load('policy'));
    }
}
