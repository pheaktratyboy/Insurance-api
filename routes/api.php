<?php

use App\Http\Controllers\AuthenticationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->group(function () {
    include 'api-auth.php';
    include 'api-employees.php';
    include 'api-agencies.php';
    include 'api-setting.php';
    include 'api-municipalities.php';
    include 'api-districts.php';
    include 'api-subscriber.php';
    include 'api-policies.php';
    include 'api-roles.php';
    include 'api-users.php';
    include 'api-medias.php';
    include 'api-reports.php';
    include 'api-company.php';
    include 'api-data-exports.php';
});


/** login process */
Route::post('/auth/login', [AuthenticationController::class,'employeeLogin'])->name('auth.login');

/** change password process */
Route::post('auth/change-password', [AuthenticationController::class,'changePassword'])->name('auth.change_password');
