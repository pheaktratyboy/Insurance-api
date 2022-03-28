<?php

namespace Tests\Feature;

use App\Exceptions\HttpException;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class EnsureUserHasRoleMiddlewareTest extends TestCase
{
    /** @test */
    public function it_will_pass_if_given_role_is_match_to_current_user()
    {
        $this->withoutExceptionHandling();
        // arrange
        Route::middleware('allow.role:manager,admin,master')->any('/_operation', function () {
            return 'OK';
        });

        // act
        $this->loginAsAdmin();
        $this->get('/_operation')->assertOk();

        $this->loginAsManager();
        $this->get('/_operation')->assertOk();

        $this->expectException(HttpException::class);
        $this->loginAsCourier();
        $this->get('/_operation');
        // assert
    }
}
