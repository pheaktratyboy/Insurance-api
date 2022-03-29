<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSubscriberRequest;
use App\Http\Requests\UpdateSubscriberRequest;
use App\Http\Resources\SubscriberResource;
use App\Models\Subscriber;
use Illuminate\Support\Facades\DB;

class SubscriberController extends Controller
{

    public function index() {

    }

    public function store(CreateSubscriberRequest $request) {

        $result = DB::transaction(function () use ($request) {
            $subscriber = new Subscriber($request->validate());
            $subscriber->createNewSubscriberWithPolicies($request);

            return $subscriber;
        });

        return new SubscriberResource($result);
    }

    public function update(UpdateSubscriberRequest $request, Subscriber $subscriber) {

    }

    public function show()
    {

    }
}
