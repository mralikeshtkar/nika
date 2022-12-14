<?php

namespace App\Http\Controllers\V1\Api\Intelligence;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Intelligence\IntelligenceExerciseService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ApiIntelligenceExerciseController extends ApiBaseController
{
    private IntelligenceExerciseService $intelligenceExerciseService;

    /**
     * @param IntelligenceExerciseService $intelligenceExerciseService
     */
    public function __construct(IntelligenceExerciseService $intelligenceExerciseService)
    {
        $this->intelligenceExerciseService = $intelligenceExerciseService;
    }

    /**
     * Get intelligence exercises as pagination.
     *
     * @OA\Get (
     *     path="/intelligence-packages/{id}/exercises",
     *     summary="لیست تمرینات هوش بصورت صفحه بندی",
     *     description="",
     *     tags={"هوش"},
     *     @OA\Parameter(
     *         description="شناسه جدول میانی",
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
     *     @OA\Parameter(
     *         description="جستجوی عنوان",
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
    public function index(Request $request,$intelligencePackage)
    {
        return $this->intelligenceExerciseService->index($request,$intelligencePackage);
    }
}
