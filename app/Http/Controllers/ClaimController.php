<?php

namespace App\Http\Controllers;

use App\Enums\BaseRole;
use App\Enums\StatusType;
use App\Http\Requests\CreateClaimRequest;
use App\Http\Requests\UpdateClaimRequest;
use App\Http\Resources\ClaimResource;
use App\Models\Claim;
use App\Models\Subscriber;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class ClaimController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole([BaseRole::Admin, BaseRole::Master])) {
            $result = QueryBuilder::for(Claim::class)
                ->allowedFilters(['subject'])
                ->defaultSort('-created_at')
                ->paginate()
                ->appends(request()->query());

        } else {
            $result = QueryBuilder::for(Claim::class)
                ->where('created_by', $user->id)
                ->allowedFilters(['subject'])
                ->defaultSort('-created_at')
                ->paginate()
                ->appends(request()->query());
        }

        return ClaimResource::collection($result);
    }

    public function store(CreateClaimRequest $request)
    {
        $result = DB::transaction(function () use ($request) {

            $subscriber = Subscriber::firstWhere('id', $request->input('subscriber_id'));
            $user_id = $subscriber->user->id;

            $claim = new Claim($request->input());
            $claim->status = StatusType::Pending;
            $claim->user_id = $user_id;

            $claim->save();

            return $claim;
        });

        return new ClaimResource($result);
    }

    public function update(UpdateClaimRequest $request, Claim $claim)
    {
        $claim->allowOnlyStatusPending();

        DB::transaction(function () use ($request, $claim) {

            $claim->update($request->input());
        });

        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Claim $claim
     * @return ClaimResource
     */
    public function show(Claim $claim)
    {
        return new ClaimResource($claim->load('subscriber'));
    }

    public function approvedClaim(Claim $claim)
    {
        $claim->notAllowIfStatusHasApproved()
            ->allowOnlyAdmin();

        $claim->status = StatusType::Approved;
        $claim->claimed_at = Carbon::now();
        $claim->update();
        $claim->confirmSubscriberHasClaimed();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function rejectedClaim(Claim $claim)
    {
        $claim->notAllowIfStatusHasApproved()
            ->allowOnlyAdmin();

        $claim->status = StatusType::Rejected;
        $claim->update();
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function cancelClaim(Claim $claim)
    {
        $claim->allowOnlyStatusPending();

        $claim->status = StatusType::Cancel;
        $claim->update();
        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Claim $claim
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function destroy(Claim $claim)
    {
        $claim->notAllowIfStatusHasApproved();

        $claim->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
