<?php

namespace App\Http\Controllers\V1\Api\Grade;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Grade\GradeService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ApiGradeController extends ApiBaseController
{

    /**
     * @var GradeService
     */
    private GradeService $gradeService;

    /**
     * ApiGradeController constructor.
     *
     * @param GradeService $gradeService
     */
    public function __construct(GradeService $gradeService)
    {
        $this->gradeService = $gradeService;
    }

    /**
     * Get grades as pagination.
     *
     * @OA\Get (
     *     path="/grades",
     *     summary="لیست مقاطع تحصیلی بصورت صفحه بندی",
     *     description="",
     *     tags={"مقاطع تحصیلی"},
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
     *         description="جستجوی نام",
     *         in="query",
     *         name="name",
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
        return $this->gradeService->index($request);
    }

    /**
     * Store a grade.
     *
     * @OA\Post(
     *     path="/grades",
     *     summary="ثبت مقطع تحصیلی",
     *     description="",
     *     tags={"مقاطع تحصیلی"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="نام"
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
        return $this->gradeService->store($request);
    }

    /**
     * Update a grade.
     *
     * @OA\Post(
     *     path="/grades/{id}",
     *     summary="بروزرسانی مقطع تحصیلی",
     *     description="",
     *     tags={"مقاطع تحصیلی"},
     *     @OA\Parameter(
     *         description="شناسه مقطع تحصیلی",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"_method","name"},
     *                 @OA\Property(
     *                     property="_method",
     *                     type="string",
     *                     default="put",
     *                     enum={"put"},
     *                     description="این مقدار باید بصورت ثابت شود",
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="نام"
     *                 )
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
    public function update(Request $request,$grade)
    {
        return $this->gradeService->update($request,$grade);
    }

    /**
     * Delete a grade.
     *
     * @OA\Delete(
     *     path="/grades/{id}",
     *     summary="حذف مقطع تحصیلی",
     *     description="",
     *     tags={"مقاطع تحصیلی"},
     *     @OA\Parameter(
     *         description="شناسه مقطع تحصیلی",
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
    public function destroy(Request $request, $grade)
    {
        return $this->gradeService->destroy($request,$grade);
    }

}
