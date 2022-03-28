<?php


namespace Tests\Unit\Middleware;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class GroupLandlordMiddlewareTest extends TestCase
{
    /** @test */
    public function will_allow_to_request_when_user_is_super_admin()
    {
        $this->loginAsMaster();

        /** set middleware to route */
        Route::middleware('group.landlord')->any('/_test', function () {
            return 'OK';
        });

        /** request action */
        $response = $this->get('/_test');
        $this->assertEquals('OK', $response->getContent());
    }
}
