<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\UserController;
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
    include 'api-employee.php';
    include 'api-agency.php';
    include 'api-medias.php';
    include 'api-setting.php';
    include 'api-municipalities.php';
    include 'api-districts.php';
    include 'api-subscriber.php';
    include 'api-policy.php';
    include 'api-roles.php';
});

/** login process */
Route::post('/auth/login', [AuthenticationController::class,'employeeLogin'])->name('auth.login');

/** register staff process */
Route::post('/auths/registerStaff', [UserController::class, 'registerStaff'])->name('auths.register_staff');
