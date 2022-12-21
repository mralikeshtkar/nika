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
     *                 required={"intelligence_id","intelligence_point_name_id","package_id","max_point"},
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
     *                     property="package_id",
     *                     type="number",
     *                     description="شناسه پکیج"
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
     *                 required={"_method","intelligence_id","intelligence_point_name_id","package_id","max_point"},
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
     *                     property="package_id",
     *                     type="number",
     *                     description="شناسه پکیج"
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
    public function update(Request $request,$intelligencePoint)
    {
        return $this->intelligencePointService->update($request,$intelligencePoint);
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
        return $this->intelligencePointService->destroy($request,$intelligencePoint);
    }
}