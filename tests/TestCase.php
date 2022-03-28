<?php

namespace Tests;

use App\Enums\BaseRole;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use App\Models\WareHouse;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Passport\Passport;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function login(User $user)
    {
        Passport::actingAs($user);

        return Auth::user();
    }

    public function loginAsMaster()
    {
        /**@var Employee $employee */
        $employee = Employee::factory()->create();

        /** assign role */
        Role::firstOrCreate(
            ['name' => BaseRole::Master],
            [
                'name'  => BaseRole::Master,
                'label' => Str::of(BaseRole::Master)->replace('_', ' ')->title(),
            ]
        );

        /**@var User $user*/
        $user = User::factory()->forEmployee($employee)->create();

        /** assign role admin */
        $user->assignRole(BaseRole::Master);

        return $this->login($user);
    }
    public function loginTMSAdmin()
    {

        /** crete ware house */
        $warehouse = WareHouse::factory()->create();

        /**@var Employee $employee */
        $employee = Employee::factory()->create([
            'ware_house_id' => $warehouse->id,
        ]);

        /** assign role */
        Role::firstOrCreate(
            ['name' => BaseRole::Admin],
            [
                'name'  => BaseRole::Admin,
                'label' => Str::of(BaseRole::Admin)->replace('_', ' ')->title(),
            ]
        );


        /**@var User $user*/
        $user = User::factory()->forEmployee($employee)->create();

        /** assign of role admin */
        $user->assignRole(BaseRole::Admin);

        return $this->login($user);
    }

    public function loginAsAdmin()
    {
        /** create company */
        $company = Company::factory()->create();

        /** crete ware house */
        $warehouse = WareHouse::factory()->create();

        /**@var Employee $employee */
        $employee = Employee::factory()->create([
            'company_id'    => $company->id,
            'ware_house_id' => $warehouse->id,
        ]);

        /** assign role */
        Role::firstOrCreate(
            ['name' => BaseRole::Admin],
            [
                'name'  => BaseRole::Admin,
                'label' => Str::of(BaseRole::Admin)->replace('_', ' ')->title(),
            ]
        );


        /**@var User $user*/
        $user = User::factory()->forEmployee($employee)->create();

        /** assign of role admin */
        $user->assignRole(BaseRole::Admin);

        return $this->login($user);
    }

    public function loginAsManager()
    {
        /** create company */
        $company = Company::factory()->create();

        /**@var Employee $employee */
        $employee = Employee::factory()->create([
            'company_id' => $company->id,
        ]);

        /** assign role */
        Role::firstOrCreate(
            ['name' => BaseRole::Manager],
            [
                'name'  => BaseRole::Manager,
                'label' => Str::of(BaseRole::Manager)->replace('_', ' ')->title(),
            ]
        );


        /**@var User $user*/
        $user = User::factory()->forEmployee($employee)->create();

        /** assign of role admin */
        $user->assignRole(BaseRole::Manager);

        return $this->login($user);
    }

    public function loginAsCourier()
    {
        /** create company */
        $company = Company::factory()->create();

        /**@var Employee $employee */
        $employee = Employee::factory()->create([
            'company_id' => $company->id,
        ]);

        /** assign role */
        Role::firstOrCreate(
            ['name' => BaseRole::Courier],
            [
                'name'  => BaseRole::Courier,
                'label' => Str::of(BaseRole::Courier)->replace('_', ' ')->title(),
            ]
        );


        /**@var User $user*/
        $user = User::factory()->forEmployee($employee)->create();

        /** assign of role admin */
        $user->assignRole(BaseRole::Courier);

        return $this->login($user);
    }
}
