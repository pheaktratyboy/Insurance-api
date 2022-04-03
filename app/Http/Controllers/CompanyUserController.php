<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCompanyUsersRequest;
use App\Http\Requests\UpdateCompanyUsersRequest;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\CompanyUserResource;
use App\Models\Company;
use App\Models\CompanyUser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class CompanyUserController extends Controller
{
    public function index()
    {
        $company = QueryBuilder::for(CompanyUser::class)
            ->allowedFilters(['name'])
            ->defaultSort('-created_at')
            ->with(['user', 'company'])
            ->paginate()
            ->appends(request()->query());

        return CompanyUserResource::collection($company);
    }

    /**
     * @param CreateCompanyUsersRequest $request
     * @param Company $company
     * @return CompanyResource
     */
    public function store(CreateCompanyUsersRequest $request, Company $company) {

        $result = DB::transaction(function () use ($request, $company) {

            return $company->addUserUnderCompany($request->input('users'))
                ->cacheSumTotalStaff()
                ->load('employees');
        });

        return new CompanyResource($result);
    }


    public function update(UpdateCompanyUsersRequest $request, CompanyUser $user) {

        DB::transaction(function () use ($request, $user) {
            $user->update($request->validated());
        });

        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param CompanyUser $companyUsers
     * @return CompanyUserResource
     */
    public function show(CompanyUser $companyUsers) {
        
        return new CompanyUserResource($companyUsers->load(['user', 'company']));
    }

    /**
     * @param CompanyUser $companyUser
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function destroy(CompanyUser $companyUser) {

        $company = $companyUser;
        $companyUser->delete();
        $company->company->cacheSumTotalStaff();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
