<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\RolePermissionController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
//Route::get('/admin/user', [UserController::class, 'fetchUser']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('verify_token',[AuthController::class,'verify_token'])->name('verify_token');
   

});
//Route::get('getPermission',[RoleController::class,'all_permission'])->name('getPermission');

Route::controller(RoleController::class)->group(function () {
    Route::get('getPermission', 'all_permission')->name('getPermission');
    Route::get('getRolePermission', 'getRolePermission')->name('getRolePermission');
    Route::get('selectedRolePermission/{id}', 'selectedRolePermission')->name('selectedRolePermission');
    Route::post('rolePermissionInsert', 'rolePermissionInsert')->name('rolePermissionInsert');
    Route::post('rolePermissionUpdate', 'rolePermissionUpdate')->name('rolePermissionUpdate');
    Route::post('rolePermissionDelete', 'rolePermissionDelete')->name('rolePermissionDelete');
    
});
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login')->name('login');
    Route::post('forgot-password','forgotPassword')->middleware('throttle:5,1')->name('forgot-password');
    Route::post('checkResetToken','checkResetToken')->middleware('throttle:5,1')->name('checkResetToken');
    Route::post('resetPassword','resetPassword')->middleware('throttle:5,1')->name('resetPassword');

   // Route::resource('roles', RoleController::class);
});


