<?php

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
    $user=\App\Models\User::first();
    $user->assignRole(\App\Enums\Role::SUPER_ADMIN['name']);
//    $user->removeRole("manager");
    dd($user->roles,$user->permissions);
});
