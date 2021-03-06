<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class CompanyController extends Controller
{

    public function index()
    {
        $result = QueryBuilder::for(Company::class)
            ->allowedFilters(['name'])
            ->defaultSort('-created_at')
            ->paginate()
            ->appends(request()->query());

        return CompanyResource::collection($result);
    }


    /**
     * @param Company $company
     * @return CompanyResource
     */
    public function show(Company $company)
    {
        $company->load('employees.user');

        return new CompanyResource($company);
    }

    /**
     * @param CreateCompanyRequest $request
     * @return CompanyResource
     */
    public function store(CreateCompanyRequest $request)
    {
        $result = DB::transaction(function () use ($request) {

            $company = new Company;
            $company->createNewCompany($request);

            if ($request->has('users')) {

                $company->addUserUnderCompany($request->input('users'))
                    ->cacheSumTotalStaff();
            }
            return $company;
        });

        return new CompanyResource($result);
    }

    /**
     * @param UpdateCompanyRequest $request
     * @param Company $company
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $company->notAllowIfItemAlreadyUsed();

        DB::transaction(function () use ($request, $company) {
            $company->update($request->input());
        });
        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Company $company
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function destroy(Company $company)
    {
        $company->notAllowIfItemAlreadyUsed();
        $company->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
