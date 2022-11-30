<?php

use App\Http\Controllers\V1\Api\Address\ApiAddressController as V1ApiAddressController;
use App\Http\Controllers\V1\Api\City\ApiCityController as V1ApiCityController;
use App\Http\Controllers\V1\Api\Grade\ApiGradeController as V1ApiGradeController;
use App\Http\Controllers\V1\Api\Job\ApiJobController as V1ApiJobController;
use App\Http\Controllers\V1\Api\Major\ApiMajorController as V1ApiMajorController;
use App\Http\Controllers\V1\Api\Package\ApiPackageController as V1ApiPackageController;
use App\Http\Controllers\V1\Api\Permission\ApiPermissionController as V1ApiPermissionController;
use App\Http\Controllers\V1\Api\Personnel\ApiPersonnelController as V1ApiPersonnelController;
use App\Http\Controllers\V1\Api\Province\ApiProvinceController as V1ProvinceController;
use App\Http\Controllers\V1\Api\PsychologicalQuestion\ApiPsychologicalQuestionController as V1ApiPsychologicalQuestionController;
use App\Http\Controllers\V1\Api\Rahjoo\ApiRahjooController as V1ApiRahjooController;
use App\Http\Controllers\V1\Api\RahjooCourse\ApiRahjooCourseController as V1ApiRahjooCourseController;
use App\Http\Controllers\V1\Api\RahjooParent\ApiRahjooParentController as V1ApiRahjooParentController;
use App\Http\Controllers\V1\Api\Role\ApiRoleController as V1ApiRoleController;
use App\Http\Controllers\V1\Api\Skill\ApiSkillController as V1ApiSkillController;
use App\Http\Controllers\V1\Api\User\ApiUserController as V1UserController;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
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

/* Version 1 */
Route::prefix('v1')->group(function (Router $router) {

    /* Users */
    $router->group([], function (Router $router) {
        $router->post('login', [V1UserController::class, 'login']);
        $router->post('login/confirm', [V1UserController::class, 'loginConfirm']);
        $router->post('login/password', [V1UserController::class, 'loginPassword']);
        $router->post('login/otp/resend', [V1UserController::class, 'loginOtpResend']);

        $router->middleware('auth:sanctum')->group(function (Router $router) {
            $router->post('users', [V1UserController::class, 'store']);
            $router->put('users/{user}', [V1UserController::class, 'update']);
        });
    });

    $router->middleware('auth:sanctum')->group(function (Router $router) {

        /* Provinces */
        $router->group([], function (Router $router) {
            $router->get('provinces', [V1ProvinceController::class, 'index']);
            $router->post('provinces', [V1ProvinceController::class, 'store']);
            $router->get('provinces/all', [V1ProvinceController::class, 'all']);
            $router->get('provinces/{province}', [V1ProvinceController::class, 'show']);
            $router->put('provinces/{province}', [V1ProvinceController::class, 'update']);
            $router->delete('provinces/{province}', [V1ProvinceController::class, 'destroy']);
        });

        /* Cities */
        $router->group([], function (Router $router) {
            $router->get('cities', [V1ApiCityController::class, 'index']);
            $router->post('cities', [V1ApiCityController::class, 'store']);
            $router->put('cities/{city}', [V1ApiCityController::class, 'update']);
            $router->delete('cities/{city}', [V1ApiCityController::class, 'destroy']);
        });

        /* Permissions */
        $router->group([], function (Router $router) {
            $router->get('permissions', [V1ApiPermissionController::class, 'index']);
        });

        /* Roles */
        $router->group([], function (Router $router) {
            $router->get('roles', [V1ApiRoleController::class, 'index']);
            $router->post('roles', [V1ApiRoleController::class, 'store']);
            $router->get('roles/all', [V1ApiRoleController::class, 'all']);
            $router->put('roles/{role}', [V1ApiRoleController::class, 'update']);
            $router->delete('roles/{role}', [V1ApiRoleController::class, 'destroy']);
        });

        /* Grades */
        $router->group([], function (Router $router) {
            $router->get('grades', [V1ApiGradeController::class, 'index']);
            $router->post('grades', [V1ApiGradeController::class, 'store']);
            $router->put('grades/{grade}', [V1ApiGradeController::class, 'update']);
            $router->delete('grades/{grade}', [V1ApiGradeController::class, 'destroy']);
        });

        /* Addresses */
        $router->group([], function (Router $router) {
            $router->get('addresses', [V1ApiAddressController::class, 'index']);
            $router->post('addresses', [V1ApiAddressController::class, 'store']);
            $router->put('addresses/{address}', [V1ApiAddressController::class, 'update']);
            $router->delete('addresses/{address}', [V1ApiAddressController::class, 'destroy']);
        });

        /* Majors */
        $router->group([], function (Router $router) {
            $router->get('majors', [V1ApiMajorController::class, 'index']);
            $router->post('majors', [V1ApiMajorController::class, 'store']);
            $router->get('majors/all', [V1ApiMajorController::class, 'all']);
            $router->put('majors/{major}', [V1ApiMajorController::class, 'update']);
            $router->delete('majors/{major}', [V1ApiMajorController::class, 'destroy']);
        });

        /* Jobs */
        $router->group([], function (Router $router) {
            $router->get('jobs', [V1ApiJobController::class, 'index']);
            $router->post('jobs', [V1ApiJobController::class, 'store']);
            $router->get('jobs/all', [V1ApiJobController::class, 'all']);
            $router->put('jobs/{job}', [V1ApiJobController::class, 'update']);
            $router->delete('jobs/{job}', [V1ApiJobController::class, 'destroy']);
        });

        /* Personnels */
        $router->group([], function (Router $router) {
            $router->get('personnels', [V1ApiPersonnelController::class, 'index']);
            $router->get('personnels/{user}', [V1ApiPersonnelController::class, 'show']);
            $router->post('personnels/{user}', [V1ApiPersonnelController::class, 'store']);
            $router->delete('personnels/{user}', [V1ApiPersonnelController::class, 'destroy']);
        });

        /* Rahjoos */
        $router->group([], function (Router $router) {
            $router->get('rahjoos', [V1ApiRahjooController::class, 'index']);
            $router->get('rahjoos/{rahjoo}', [V1ApiRahjooController::class, 'show']);
            $router->post('rahjoos/{user}', [V1ApiRahjooController::class, 'store']);
            $router->delete('rahjoos/{rahjoo}', [V1ApiRahjooController::class, 'destroy']);
        });

        /* Rahjoo parents */
        $router->group([], function (Router $router) {
            $router->get('rahjoo-parents', [V1ApiRahjooParentController::class, 'index']);
            $router->post('rahjoo-parents/{rahjoo}', [V1ApiRahjooParentController::class, 'store']);
            $router->delete('rahjoo-parents/{rahjooParent}', [V1ApiRahjooParentController::class, 'destroy']);
        });

        /* Rahjoo courses */
        $router->group([], function (Router $router) {
            $router->get('rahjoo-courses', [V1ApiRahjooCourseController::class, 'index']);
            $router->post('rahjoo-courses/{rahjoo}', [V1ApiRahjooCourseController::class, 'store']);
            $router->delete('rahjoo-courses/{rahjooCourse}', [V1ApiRahjooCourseController::class, 'destroy']);
        });

        /* Psychological questions */
        $router->group([], function (Router $router) {
//            $router->get('psychological-questions', [V1ApiPsychologicalQuestionController::class, 'index']);
            $router->post('psychological-questions/{rahjoo}', [V1ApiPsychologicalQuestionController::class, 'store']);
//            $router->delete('psychological-questions/{rahjooCourse}', [V1ApiPsychologicalQuestionController::class, 'destroy']);
        });

        /* Skills */
        $router->group([], function (Router $router) {
            $router->get('skills', [V1ApiSkillController::class, 'index']);
            $router->post('skills', [V1ApiSkillController::class, 'store']);
            $router->get('skills/all', [V1ApiSkillController::class, 'all']);
            $router->put('skills/{skill}', [V1ApiSkillController::class, 'update']);
            $router->delete('skills/{skill}', [V1ApiSkillController::class, 'update']);
        });

        /* Packages */
        $router->group([], function (Router $router) {
            $router->get('packages', [V1ApiPackageController::class, 'index']);
            $router->post('packages', [V1ApiPackageController::class, 'store']);
            $router->get('packages/{package}', [V1ApiPackageController::class, 'show']);
            $router->put('packages/{package}', [V1ApiPackageController::class, 'update']);
            $router->delete('packages/{package}', [V1ApiPackageController::class, 'update']);
        });

    });

});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('stream', function () {

})->name('stream');
