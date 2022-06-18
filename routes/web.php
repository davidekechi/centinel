<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['middleware' => ['auth']], function() {

        Route::get('/home', function () {
                return redirect("/"); 
        });

        Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

        Route::post('/getlatestdata', [App\Http\Controllers\HomeController::class, 'getLatestData']);

        Route::get('/getparkdata', [App\Http\Controllers\SynchController::class, 'getParkData']);

        Route::get('/getcameradata', [App\Http\Controllers\SynchController::class, 'getCameraData']);

        Route::post('/updatetenantdata', [App\Http\Controllers\SynchController::class, 'updateTenantData']);

        Route::post('/updatetariffdata', [App\Http\Controllers\SynchController::class, 'updateTariffData']);

        Route::patch('/update', [App\Http\Controllers\PlateController::class, 'update']);

        Route::get('/plateinserter', [App\Http\Controllers\PlateController::class, 'plate']);

        Route::get('/plateinserter/tenant', [App\Http\Controllers\PlateController::class, 'tenant']);

        Route::get('/plateinserter/tariff', [App\Http\Controllers\PlateController::class, 'tariff']);

        Route::post('/plateinserter/store', [App\Http\Controllers\PlateController::class, 'store']);

        Route::post('/addcamera', [App\Http\Controllers\SettingsController::class, 'addCamera']);

        Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
});

Route::post('/updateusers', [App\Http\Controllers\SynchController::class, 'updateUsers']);

Route::post('/updateassignmentdata', [App\Http\Controllers\SynchController::class, 'updateAssignmentData']);

Auth::routes();
