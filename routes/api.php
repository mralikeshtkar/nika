<?php

use App\Http\Controllers\V1\Api\Address\ApiAddressController as V1ApiAddressController;
use App\Http\Controllers\V1\Api\ApiUploadFileController;
use App\Http\Controllers\V1\Api\City\ApiCityController as V1ApiCityController;
use App\Http\Controllers\V1\Api\DocumentGroup\ApiDocumentGroupController as V1ApiDocumentGroupController;
use App\Http\Controllers\V1\Api\Exercise\ApiExerciseController as V1ApiExerciseController;
use App\Http\Controllers\V1\Api\Grade\ApiGradeController as V1ApiGradeController;
use App\Http\Controllers\V1\Api\Intelligence\ApiIntelligenceController as V1ApiIntelligenceController;
use App\Http\Controllers\V1\Api\Intelligence\ApiIntelligenceExerciseController as V1ApiIntelligenceExerciseController;
use App\Http\Controllers\V1\Api\IntelligenceFeedback\ApiIntelligenceFeedbackController as V1ApiIntelligenceFeedbackController;
use App\Http\Controllers\V1\Api\IntelligencePoint\ApiIntelligencePointController as V1ApiIntelligencePointController;
use App\Http\Controllers\V1\Api\IntelligencePointName\ApiIntelligencePointNameController as V1ApiIntelligencePointNameController;
use App\Http\Controllers\V1\Api\Job\ApiJobController as V1ApiJobController;
use App\Http\Controllers\V1\Api\Major\ApiMajorController as V1ApiMajorController;
use App\Http\Controllers\V1\Api\Package\ApiPackageController as V1ApiPackageController;
use App\Http\Controllers\V1\Api\Package\ApiIntelligencePackageController as V1ApiIntelligencePackageController;
use App\Http\Controllers\V1\Api\Permission\ApiPermissionController as V1ApiPermissionController;
use App\Http\Controllers\V1\Api\Personnel\ApiPersonnelController as V1ApiPersonnelController;
use App\Http\Controllers\V1\Api\Province\ApiProvinceController as V1ProvinceController;
use App\Http\Controllers\V1\Api\PsychologicalQuestion\ApiPsychologicalQuestionController as V1ApiPsychologicalQuestionController;
use App\Http\Controllers\V1\Api\Question\ApiQuestionAnswerTypeController as V1ApiQuestionAnswerTypeController;
use App\Http\Controllers\V1\Api\Question\ApiQuestionController as V1ApiQuestionController;
use App\Http\Controllers\V1\Api\Question\ApiQuestionPointController as V1ApiQuestionPointController;
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

        /* Users */
        $router->group([], function (Router $router) {
            $router->get('user', [V1UserController::class, 'currentUser']);
            $router->put('user/information', [V1UserController::class, 'informationUser']);
            $router->post('logout', [V1UserController::class, 'logout']);
        });

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
            $router->delete('packages/{package}', [V1ApiPackageController::class, 'destroy']);
            $router->post('packages/{package}/upload-video', [V1ApiPackageController::class, 'uploadVideo']);
            $router->put('packages/{package}/completed', [V1ApiPackageController::class, 'completed']);
            $router->put('packages/{package}/uncompleted', [V1ApiPackageController::class, 'uncompleted']);
            $router->put('packages/{package}/active-status', [V1ApiPackageController::class, 'activeStatus']);
            $router->put('packages/{package}/inactive-status', [V1ApiPackageController::class, 'inactiveStatus']);

            /* Package intelligences */
            $router->group([], function (Router $router) {
                $router->get('packages/{package}/intelligences', [V1ApiIntelligencePackageController::class, 'index']);
                $router->get('intelligence-packages/{intelligencePackage}', [V1ApiIntelligencePackageController::class, 'show']);
                $router->get('intelligence-packages/{intelligencePackage}/points', [V1ApiIntelligencePackageController::class, 'points']);
                $router->get('intelligence-packages/{intelligencePackage}/feedbacks', [V1ApiIntelligencePackageController::class, 'feedbacks']);
                $router->post('packages/{package}/intelligences', [V1ApiIntelligencePackageController::class, 'store']);
                $router->delete('intelligence-packages/{intelligencePackage}', [V1ApiIntelligencePackageController::class, 'destroy']);
                $router->put('intelligence-packages/{intelligencePackage}/completed', [V1ApiIntelligencePackageController::class, 'completed']);
                $router->put('intelligence-packages/{intelligencePackage}/uncompleted', [V1ApiIntelligencePackageController::class, 'uncompleted']);
            });
        });

        /* Exercises */
        $router->group([], function (Router $router) {
            $router->get('exercises', [V1ApiExerciseController::class, 'index']);
            $router->get('exercises/{exercise}', [V1ApiExerciseController::class, 'show']);
            $router->post('exercises', [V1ApiExerciseController::class, 'store']);
            $router->put('exercises/{exercise}', [V1ApiExerciseController::class, 'update']);
            $router->delete('exercises/{exercise}', [V1ApiExerciseController::class, 'destroy']);
            $router->get('exercises/{exercise}/questions', [V1ApiExerciseController::class, 'questions']);

            /* Exercise questions */
            $router->group([], function (Router $router) {
                $router->post('exercises/{exercise}/questions', [V1ApiQuestionController::class, 'store']);
                $router->post('questions/{question}/upload-file', [V1ApiQuestionController::class, 'uploadFile']);
                $router->delete('questions/{question}/remove-file', [V1ApiQuestionController::class, 'removeFile']);
            });
        });

        /* Questions */
        $router->group([], function (Router $router) {
            $router->get('questions/{question}', [V1ApiQuestionController::class, 'show']);
            $router->put('questions/{question}', [V1ApiQuestionController::class, 'update']);
            $router->delete('questions/{question}', [V1ApiQuestionController::class, 'destroy']);
            $router->put('questions/{question}/change-file-priority', [V1ApiQuestionController::class, 'changeFilePriority']);
            $router->get('questions/{question}/answer-types', [V1ApiQuestionController::class, 'answerTypes']);

            /* Question answer types */
            $router->group([], function (Router $router) {
                $router->post('questions/{question}/answer-types', [V1ApiQuestionAnswerTypeController::class, 'store']);
                $router->put('questions/{question}/answer-types/change-priority', [V1ApiQuestionAnswerTypeController::class, 'changePriority']);
                $router->put('question-answer-types/{questionAnswerTypes}', [V1ApiQuestionAnswerTypeController::class, 'update']);
                $router->delete('question-answer-types/{questionAnswerTypes}', [V1ApiQuestionAnswerTypeController::class, 'destroy']);
            });

            /* Question points */
            $router->group([], function (Router $router) {
                $router->post('questions/{question}/points', [V1ApiQuestionPointController::class, 'store']);
                $router->put('questions/{question}/update-points', [V1ApiQuestionPointController::class, 'update']);
                $router->delete('questions/{question}/destroy-points', [V1ApiQuestionPointController::class, 'destroy']);
            });
        });

        /* Intelligences */
        $router->group([], function (Router $router) {
            $router->get('intelligences', [V1ApiIntelligenceController::class, 'index']);
            $router->post('intelligences', [V1ApiIntelligenceController::class, 'store']);
            $router->get('intelligences/all', [V1ApiIntelligenceController::class, 'all']);
            $router->get('intelligences/{intelligence}', [V1ApiIntelligenceController::class, 'show']);
            $router->put('intelligences/{intelligence}', [V1ApiIntelligenceController::class, 'update']);
            $router->delete('intelligences/{intelligence}', [V1ApiIntelligenceController::class, 'destroy']);

            /* Intelligence exercises */
            $router->group([], function (Router $router) {
                $router->get('intelligence-packages/{intelligencePackage}/exercises', [V1ApiIntelligenceExerciseController::class, 'index']);
            });
        });

        /* Intelligence point names */
        $router->group([], function (Router $router) {
            $router->get('intelligence-point-names', [V1ApiIntelligencePointNameController::class, 'index']);
            $router->get('intelligence-point-names/all', [V1ApiIntelligencePointNameController::class, 'all']);
            $router->post('intelligence-point-names', [V1ApiIntelligencePointNameController::class, 'store']);
            $router->put('intelligence-point-names/{intelligencePointName}', [V1ApiIntelligencePointNameController::class, 'update']);
            $router->delete('intelligence-point-names/{intelligencePointName}', [V1ApiIntelligencePointNameController::class, 'destroy']);
        });

        /* Intelligence point */
        $router->group([], function (Router $router) {
            $router->get('intelligence-points', [V1ApiIntelligencePointController::class, 'index']);
            $router->post('intelligence-points', [V1ApiIntelligencePointController::class, 'store']);
            $router->post('intelligence-points/multiple', [V1ApiIntelligencePointController::class, 'storeMultiple']);
            $router->put('intelligence-points/{intelligencePoint}', [V1ApiIntelligencePointController::class, 'update']);
            $router->delete('intelligence-points/{intelligencePoint}', [V1ApiIntelligencePointController::class, 'destroy']);
        });

        /* Intelligence feedbacks */
        $router->group([], function (Router $router) {
            $router->get('intelligence-feedbacks', [V1ApiIntelligenceFeedbackController::class, 'index']);
            $router->post('intelligence-feedbacks', [V1ApiIntelligenceFeedbackController::class, 'store']);
            $router->post('intelligence-feedbacks/multiple', [V1ApiIntelligenceFeedbackController::class, 'storeMultiple']);
            $router->put('intelligence-feedbacks/{intelligenceFeedback}', [V1ApiIntelligenceFeedbackController::class, 'update']);
            $router->delete('intelligence-feedbacks/{intelligenceFeedback}', [V1ApiIntelligenceFeedbackController::class, 'destroy']);
        });

        /* Document groups */
        $router->group([], function (Router $router) {
            $router->get('document-groups', [V1ApiDocumentGroupController::class, 'index']);
            $router->post('document-groups', [V1ApiDocumentGroupController::class, 'store']);
            $router->put('document-groups/{documentGroup}', [V1ApiDocumentGroupController::class, 'update']);
            $router->delete('document-groups/{documentGroup}', [V1ApiDocumentGroupController::class, 'destroy']);
        });

    });

    $router->get('upload/{media}', [ApiUploadFileController::class, 'show']);
    $router->post('upload', [ApiUploadFileController::class, 'file']);

});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
