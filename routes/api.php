<?php

use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\API\MDRUsersController;
use App\Http\Controllers\API\TokenizationController;
use App\Http\Controllers\API\SignoutController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('mdrusers/usercheck/{username}', [MDRUsersController::class, 'checkusers']);
Route::get('mdrusers/tokenizationCheck/{userID}', [TokenizationController::class, 'gettokenization']);
Route::post('mdrusers/devregistration', [MDRUsersController::class, 'store']);

Route::prefix('developers')->group(function() {
    Route::post('/devlogin', [MDRUsersController::class, 'userlogin']);
    Route::get('/tokenidentify/{userID}', [TokenizationController::class, 'tokenIdentify']);
    Route::get('/branch/getallbranches', [BranchController::class, 'getBranches']);
    Route::put('/branchroute/pushroute/uid/{userID}', [BranchController::class, 'routeupdater']);
    Route::put('/devbranch/signout/uid/{userID}', [SignoutController::class, 'signout']);
    Route::get('/getallusers', [MDRUsersController::class, 'index']);
});
