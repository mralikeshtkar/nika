<?php

namespace App\Http\Controllers\V1\Api\Exercise;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Models\Package;
use App\Services\V1\Exercise\ExerciseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OpenApi\Annotations as OA;

class ApiExerciseController extends ApiBaseController
{
    /**
     * @var ExerciseService
     */
    private ExerciseService $exerciseService;

    /**
     * @param ExerciseService $exerciseService
     */
    public function __construct(ExerciseService $exerciseService)
    {
        $this->exerciseService = $exerciseService;
    }

    /**
     * Get exercises as pagination.
     *
     * @OA\Get (
     *     path="/exercises",
     *     summary="لیست تمرینات بصورت صفحه بندی",
     *     description="",
     *     tags={"تمرینات"},
     *     @OA\Parameter(
     *         description="شماره صفحه",
     *         in="query",
     *         name="page",
     *         required=true,
     *         example=1,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Parameter(
     *         description="تعداد نمایش در هر صفحه",
     *         in="query",
     *         name="perPage",
     *         example=10,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Parameter(
     *         description="عنوان",
     *         in="query",
     *         name="title",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function index(Request $request)
    {
        return $this->exerciseService->index($request);
    }

    /**
     * @OA\Get(
     *     path="/exercises/{id}",
     *     summary="دریافت یک تمرین",
     *     description="",
     *     tags={"تمرینات"},
     *     @OA\Parameter(
     *         description="شناسه تمرین",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function show(Request $request, $exercise)
    {
        return $this->exerciseService->show($exercise);
    }

    /**
     * Store an exercise.
     *
     * @OA\Post(
     *     path="/exercises",
     *     summary="ثبت تمرین",
     *     description="",
     *     tags={"تمرینات"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"intelligence_package_id","title"},
     *                 @OA\Property(
     *                     property="intelligence_package_id",
     *                     type="number",
     *                     description="شناسه جدول میانی هوش پکیج"
     *                 ),
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="عنوان"
     *                 ),
     *                 @OA\Property(
     *                     property="is_locked",
     *                     type="number",
     *                     enum={1,0},
     *                     description="آیا تمرین قفل است ؟ 1بله 0خیر",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="ثبت با موفقیت انجام شد",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function store(Request $request)
    {
        return $this->exerciseService->store($request);
    }

    /**
     * Update an exercise.
     *
     * @OA\Post(
     *     path="/exercises/{id}",
     *     summary="بروزرسانی تمرین",
     *     description="",
     *     tags={"تمرینات"},
     *     @OA\Parameter(
     *         description="شناسه تمرین",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"_method","is_locked","title"},
     *                 @OA\Property(
     *                     property="_method",
     *                     type="string",
     *                     default="put",
     *                     enum={"put"},
     *                     description="این مقدار باید بصورت ثابت شود",
     *                 ),
     *                 @OA\Property(
     *                     property="intelligence_package_id",
     *                     type="number",
     *                     description="شناسه جدول میانی هوش پکیج"
     *                 ),
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="عنوان"
     *                 ),
     *                 @OA\Property(
     *                     property="is_locked",
     *                     type="number",
     *                     enum={1,0},
     *                     description="آیا تمرین قفل است ؟ 1بله 0خیر",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function update(Request $request, $exercise)
    {
        return $this->exerciseService->update($request,$exercise);
    }

    /**
     * Get exercise questions as pagination.
     *
     * @OA\Get (
     *     path="/exercises/{id}/questions",
     *     summary="لیست سوال های تمرین بصورت صفحه بندی",
     *     description="",
     *     tags={"تمرینات"},
     *     @OA\Parameter(
     *         description="شناسه تمرین",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Parameter(
     *         description="شماره صفحه",
     *         in="query",
     *         name="page",
     *         required=true,
     *         example=1,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Parameter(
     *         description="تعداد نمایش در هر صفحه",
     *         in="query",
     *         name="perPage",
     *         example=10,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function questions(Request $request, $exercise)
    {
        return $this->exerciseService->questions($request,$exercise);
    }

    /**
     * Destroy an exercise.
     *
     * @OA\Delete(
     *     path="/exercises/{id}",
     *     summary="حذف تمرین",
     *     description="",
     *     tags={"تمرینات"},
     *     @OA\Parameter(
     *         description="شناسه تمرین",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function destroy(Request $request,$exercise)
    {
        return $this->exerciseService->destroy($request,$exercise);
    }
}
