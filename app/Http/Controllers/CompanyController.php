<?php

namespace App\Http\Controllers;

use App\Enums\BaseRole;
use App\Http\Requests\CreateCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\CompanyUnderUserResource;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class CompanyController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole([BaseRole::Admin, BaseRole::Master])) {
            $result = QueryBuilder::for(Company::class)
                ->allowedFilters(['name'])
                ->defaultSort('-created_at')
                ->paginate()
                ->appends(request()->query());

            return CompanyResource::collection($result);
        } else {
            $result = QueryBuilder::for(CompanyUser::class)
                ->with('company')
                ->where('user_id', $user->id)
                ->allowedFilters(['name'])
                ->defaultSort('-created_at')
                ->paginate()
                ->appends(request()->query());

            return CompanyUnderUserResource::collection($result);
        }
    }

    /**
     * @param Company $company
     * @return CompanyResource
     */
    public function show(Company $company)
    {
        // check this user under company or not
        $company->checkPermissionViewData();

        return new CompanyResource($company);
    }

    public function assignSubscriberToCompany(Request $request)
    {
        $request->validate([
            'subscriber_id' => 'required',
            'company_id'    => 'required',
        ]);

        $subscriber = Subscriber::firstWhere('id', $request->input('subscriber_id'));
        $user = $subscriber->user;

        if ($user) {
            $result = DB::transaction(function () use ($user, $request) {
                $company = Company::firstWhere('id', $request->input('company_id'));

                $param = [['user_id' => $user->id]];
                $company->addSubscriberUnderCompany($param)->cacheSumTotalSubscriber()->load('employees');

                return $company;
            });

            return new CompanyResource($result);
        }
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

            $user = auth()->user();
            $users = [];

            if ($user->hasRole(BaseRole::Staff)) {
                $users[] = ['user_id' => $user->id];
            } elseif ($user->hasRole(BaseRole::Agency)) {
                $users[] = ['user_id' => $user->id];
                $users[] = ['user_id' => $user->created_by];
            }

            $company->addUserUnderCompany($users)
                ->cacheSumTotalStaff();

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
