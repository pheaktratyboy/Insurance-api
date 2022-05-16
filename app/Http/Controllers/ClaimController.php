<?php

namespace App\Http\Controllers;

use App\Enums\BaseRole;
use App\Enums\StatusType;
use App\Http\Requests\CreateClaimRequest;
use App\Http\Requests\UpdateClaimRequest;
use App\Http\Resources\ClaimResource;
use App\Models\Claim;
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
            $claim = new Claim($request->input());
            $claim->status = StatusType::Pending;

            $claim->save();

            return $claim;
        });

        return new ClaimResource($result);
    }

    public function update(UpdateClaimRequest $request, Claim $claim)
    {
        $claim->allowOnlyStatusPending();
    }

    /**
     * @param Claim $claim
     * @return ClaimResource
     */
    public function show(Claim $claim)
    {
        return new ClaimResource($claim);
    }

    public function approvedClaim(Claim $claim)
    {
        $claim->notAllowIfStatusApproved();
    }

    public function rejectedClaim(Claim $claim)
    {
        $claim->notAllowIfStatusApproved();
    }

    public function cancelClaim(Claim $claim)
    {
        $claim->allowOnlyStatusPending();
    }

    /**
     * @param Claim $claim
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function destroy(Claim $claim)
    {
        $claim->notAllowIfStatusApproved();

        $claim->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
