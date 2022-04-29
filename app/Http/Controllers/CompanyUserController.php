<?php

namespace App\Http\Controllers;

use App\Enums\BaseRole;
use App\Http\Requests\CreateCompanyUsersRequest;
use App\Http\Requests\UpdateCompanyUsersRequest;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\CompanyUserResource;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class CompanyUserController extends Controller
{
    public function index(Company $company)
    {
        $query = QueryBuilder::for(CompanyUser::where('company_id', $company->id))
            ->allowedFilters(['name'])
            ->defaultSort('-created_at')
            ->with(['user', 'company'])
            ->paginate()
            ->appends(request()->query());

        return CompanyUserResource::collection($query);
    }

    /**
     * @param CreateCompanyUsersRequest $request
     * @param Company $company
     * @return CompanyResource
     */
    public function store(CreateCompanyUsersRequest $request, Company $company) {

        $users = $request->input('users');
        $userQuery = User::whereIn('id', $users)->get();

        foreach ($userQuery as $param) {

            if (!$param->hasRole(BaseRole::Subscriber)) {
                abort('422', 'the user with is not exists');
                break;
            }
        }

        $result = DB::transaction(function () use ($users, $company) {

            return $company->setUsersUnderCompany($users)
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

       $user = User::find($companyUser->user_id);

       if ($user) {
           $profile = $user->profile;

       }
        echo json_encode($user->profile);
       dd();

        $company = $companyUser;

        dd();
        $companyUser->delete();
        $company->company->cacheSumTotalStaff();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
