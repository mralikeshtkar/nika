<?php

use App\Http\Controllers\V1\Api\IntelligencePointName\ApiIntelligencePointNameController;
use App\Http\Controllers\V1\Api\Media\ApiMediaController as V1ApiMediaController;
use App\Http\Controllers\V1\Web\RahjooSupport\WebRahjooSupportController as V1WebRahjooSupportController;
use App\Responses\Api\ApiResponse;
use Illuminate\Routing\Router;
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

Route::prefix('v1')->group(function (Router $router){

    $router->get('media/{media}/{file?}', [V1ApiMediaController::class, 'download'])
        ->middleware('signed')
        ->name('media.download');

    $router->get('rahjoo-supports/payments/verify', [V1WebRahjooSupportController::class, 'verifyPayment']);

});

Route::get('/', function (\Illuminate\Http\Request $request) {
    dd($request->all());
//    $start=today()->subYears(10);
    $start=today()->subDays(10);
    $today=today();
    dd($today->diffInYears($start));
    dd(\App\Models\User::first()->generateToken());
    return view('welcome');
    $user=\App\Models\User::first();
    $user->assignRole(\App\Enums\Role::SUPER_ADMIN['name']);
//    $user->removeRole("manager");
    dd($user->roles,$user->permissions);
});
