<?php


namespace Tests\Unit;

use App\Enums\CustomHeader;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class CompanyCanResolveGloballyTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function wil_return_false_when_user_not_login()
    {
        $this->assertFalse(resolve('company'));
    }

    /** @test */
    public function it_will_return_false_when_header_request_of_company_not_provided()
    {
        $this->withoutExceptionHandling();
        $this->loginAsMaster();
        Route::get('/_test', fn () => resolve('company'));
        $this->assertFalse(resolve('company'));
    }

    /** @test */
    public function can_resolve()
    {
        $user     = $this->loginAsAdmin();
        $employee = $user->profile;

        Route::get('/_test', fn () => resolve('company'));

        $response = $this->withHeaders([CustomHeader::Company => $employee->company_id])->get('/_test');
        $this->assertEquals($employee->company_id, $response->json('id'));
    }
}
