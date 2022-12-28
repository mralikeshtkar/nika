<?php

namespace App\Http\Controllers\V1\Api\IntelligencePoint;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\IntelligencePoint\IntelligencePointService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ApiIntelligencePointController extends ApiBaseController
{
    /**
     * @var IntelligencePointService
     */
    private IntelligencePointService $intelligencePointService;

    /**
     * @param IntelligencePointService $intelligencePointService
     */
    public function __construct(IntelligencePointService $intelligencePointService)
    {
        $this->intelligencePointService = $intelligencePointService;
    }

    /**
     * Get intelligence points as pagination.
     *
     * @OA\Get (
     *     path="/intelligence-points",
     *     summary="لیست امتیاز های هوش بصورت صفحه بندی",
     *     description="",
     *     tags={"امتیاز هوش"},
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
     *         description="نام",
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
        return $this->intelligencePointService->index($request);
    }

    /**
     * Store a intelligence point.
     *
     * @OA\Post(
     *     path="/intelligence-points",
     *     summary="ثبت امتیاز هوش",
     *     description="",
     *     tags={"امتیاز هوش"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"intelligence_id","intelligence_point_name_id","max_point"},
     *                 @OA\Property(
     *                     property="intelligence_id",
     *                     type="string",
     *                     description="شناسه هوش"
     *                 ),
     *                 @OA\Property(
     *                     property="intelligence_point_name_id",
     *                     type="number",
     *                     description="شناسه نام امتیاز"
     *                 ),
     *                 @OA\Property(
     *                     property="max_point",
     *                     type="number",
     *                     description="حداکثر نمره"
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
        return $this->intelligencePointService->store($request);
    }

    /**
     * Store multiple intelligence point.
     *
     * @OA\Post(
     *     path="/intelligence-points/multiple",
     *     summary="ثبت چندتایی امتیاز هوش",
     *     description="",
     *     tags={"امتیاز هوش"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"intelligence_id"},
     *                 @OA\Property(
     *                     property="intelligence_id",
     *                     type="number",
     *                     description="شناسه هوش"
     *                 ),
     *                 @OA\Property(
     *                     property="points",
     *                     type="array",
     *                     description="ارزش ها",
     *                     @OA\Items(
     *                        type="object",
     *                        @OA\Property(property="intelligence_point_name_id", type="number"),
     *                        @OA\Property(property="max_point", type="number")
     *                     )
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
    public function storeMultiple(Request $request)
    {
        return $this->intelligencePointService->storeMultiple($request);
    }

    /**
     * Update a intelligence point.
     *
     * @OA\Post(
     *     path="/intelligence-points/{id}",
     *     summary="بروزرسانی امتیاز هوش",
     *     description="",
     *     tags={"امتیاز هوش"},
     *     @OA\Parameter(
     *         description="شناسه امتیاز هوش",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"_method","intelligence_id","intelligence_point_name_id","max_point"},
     *                 @OA\Property(
     *                     property="_method",
     *                     type="string",
     *                     default="put",
     *                     enum={"put"},
     *                     description="این مقدار باید بصورت ثابت شود",
     *                 ),
     *                 @OA\Property(
     *                     property="intelligence_id",
     *                     type="string",
     *                     description="شناسه هوش"
     *                 ),
     *                 @OA\Property(
     *                     property="intelligence_point_name_id",
     *                     type="number",
     *                     description="شناسه نام امتیاز"
     *                 ),
     *                 @OA\Property(
     *                     property="max_point",
     *                     type="number",
     *                     description="حداکثر نمره"
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
    public function update(Request $request, $intelligencePoint)
    {
        return $this->intelligencePointService->update($request, $intelligencePoint);
    }

    /**
     * Delete a intelligence point.
     *
     * @OA\Delete(
     *     path="/intelligence-points/{id}",
     *     summary="حذف امتیاز هوش",
     *     description="",
     *     tags={"امتیاز هوش"},
     *     @OA\Parameter(
     *         description="شناسه امتیاز هوش",
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
    public function destroy(Request $request, $intelligencePoint)
    {
        return $this->intelligencePointService->destroy($request, $intelligencePoint);
    }
}
