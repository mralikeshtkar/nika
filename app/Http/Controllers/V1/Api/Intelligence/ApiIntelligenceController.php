<?php

namespace App\Http\Controllers\V1\Api\Intelligence;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Intelligence\IntelligenceService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ApiIntelligenceController extends ApiBaseController
{
    /**
     * @var IntelligenceService
     */
    private IntelligenceService $intelligenceService;

    /**
     * @param IntelligenceService $intelligenceService
     */
    public function __construct(IntelligenceService $intelligenceService)
    {
        $this->intelligenceService = $intelligenceService;
    }

    /**
     * Get intelligences as pagination.
     *
     * @OA\Get (
     *     path="/intelligences",
     *     summary="لیست هوش ها بصورت صفحه بندی",
     *     description="",
     *     tags={"هوش"},
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
    public function index(Request $request)
    {
        return $this->intelligenceService->index($request);
    }

    /**
     * Store a intelligence.
     *
     * @OA\Post(
     *     path="/intelligences",
     *     summary="ثبت هوش",
     *     description="",
     *     tags={"هوش"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title","is_completed"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="عنوان"
     *                 ),
     *                 @OA\Property(
     *                     property="is_completed",
     *                     type="string",
     *                     enum={1,0},
     *                     description="وضعیت تکمیل - بصورت boolean باید باشد",
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
        return $this->intelligenceService->store($request);
    }

    /**
     * Update a intelligence.
     *
     * @OA\Post(
     *     path="/intelligences/{id}",
     *     summary="ثبت هوش",
     *     description="",
     *     tags={"هوش"},
     *     @OA\Parameter(
     *         description="شناسه هوش",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"_method","title","is_completed"},
     *                 @OA\Property(
     *                     property="_method",
     *                     type="string",
     *                     default="put",
     *                     enum={"put"},
     *                     description="این مقدار باید بصورت ثابت شود",
     *                 ),
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="عنوان"
     *                 ),
     *                 @OA\Property(
     *                     property="is_completed",
     *                     type="string",
     *                     enum={1,0},
     *                     description="وضعیت تکمیل - بصورت boolean باید باشد",
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
    public function update(Request $request, $intelligence)
    {
        return $this->intelligenceService->update($request,$intelligence);
    }

    /**
     * Delete a intelligence.
     *
     * @OA\Delete(
     *     path="/intelligences/{id}",
     *     summary="حذف هوش",
     *     description="",
     *     tags={"هوش"},
     *     @OA\Parameter(
     *         description="شناسه هوش",
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
    public function destroy(Request $request, $intelligence)
    {
        return $this->intelligenceService->destroy($request,$intelligence);
    }
}
