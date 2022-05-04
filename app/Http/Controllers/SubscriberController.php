<?php

namespace App\Http\Controllers;

use App\Enums\BaseRole;
use App\Enums\TrackingType;
use App\Http\Requests\CreateSubscriberRequest;
use App\Http\Requests\UpdateSubscriberRequest;
use App\Http\Resources\SubscriberResource;
use App\Models\Company;
use App\Models\Subscriber;
use App\Models\TrackingHistory;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\QueryBuilder;
use function Sodium\add;

class SubscriberController extends Controller
{
    public function index() {

        $user = auth()->user();

        if ($user->hasRole(BaseRole::Staff)) {

            $agencyId = User::where('created_by', $user->id)->get();
            $all = collect($agencyId)->pluck('id')->push($user->id);

            $subscriber = QueryBuilder::for(Subscriber::class)
                ->whereIn('user_id', $all)
                ->allowedFilters(['name_kh', 'name_en'])
                ->defaultSort('-created_at')
                ->paginate()
                ->appends(request()->query());

        } else if ($user->hasRole(BaseRole::Agency)) {

            $subscriber = QueryBuilder::for(Subscriber::class)
                ->where('user_id', $user->id)
                ->allowedFilters(['name_kh', 'name_en'])
                ->defaultSort('-created_at')
                ->paginate()
                ->appends(request()->query());

        } else {

            $subscriber = QueryBuilder::for(Subscriber::class)
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
             * User
             */
            $fullName = $request->input('name_en');
            $param = [
                'username'              => $fullName,
                'full_name'             => $fullName,
                'email'                 => $fullName,
                'phone_number'          => $request->phone_number,
                'password'              => bcrypt('123456'),
                'force_change_password' => true,
                'disabled'              => false,
                'activated'             => true,
                'activated_at'          => now(),
                'remember_token'        => Str::random(10),
            ];

            /**@var User $user*/
            $user = $subscriber->user_profile()->create($param);
            $user->assignRole(BaseRole::Subscriber);

            /**
             * Company User
             */
            if ($request->has('company_id')) {

                $company = Company::find($request->company_id);

                if ($company) {
                    $company->setUsersUnderCompany([['user_id' => $user->id]])
                        ->cacheSumTotalStaff();
                }
            }

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

    /**
     * @param User $user
     * @return SubscriberResource
     */
    public function showSubscribeByUser(User $user)
    {
        $profile = $user->profile->load(['company','subscriber_policies.policy']);
        return new SubscriberResource($profile);
    }
}
