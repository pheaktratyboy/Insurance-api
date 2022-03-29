<?php

namespace App\Http\Controllers;


use App\Http\Requests\CreatePolicyRequest;
use App\Http\Requests\UpdatePolicyRequest;
use App\Http\Resources\PolicyResource;
use App\Models\Policy;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;

class PolicyController extends Controller
{
    public function index()
    {
        $policies = QueryBuilder::for(Policy::class)
            ->allowedFilters(['name'])
            ->paginate()
            ->appends(request()->query());

        return PolicyResource::collection($policies);
    }

    /**
     * @param CreatePolicyRequest $request
     * @return PolicyResource
     */
    public function store(CreatePolicyRequest $request) {
        $policies = Policy::create($request->input());
        return new PolicyResource($policies);
    }

    /**
     * @param UpdatePolicyRequest $request
     * @param Policy $policy
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function update(UpdatePolicyRequest $request, Policy $policy) {

        $policy->update($request->input());
        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Policy $policy
     * @return PolicyResource
     */
    public function show(Policy $policy) {
        return new PolicyResource($policy);
    }
}
