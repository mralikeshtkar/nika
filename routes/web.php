<?php

use App\Http\Controllers\V1\Api\IntelligencePointName\ApiIntelligencePointNameController;
use App\Responses\Api\ApiResponse;
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

Route::get('/', function (\Illuminate\Http\Request $request) {
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
