<?php

namespace App\Http\Controllers\V1\Api\IntelligencePointName;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\IntelligencePointName\IntelligencePointNameService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ApiIntelligencePointNameController extends ApiBaseController
{
    /**
     * @var IntelligencePointNameService
     */
    private IntelligencePointNameService $intelligencePointNameService;

    /**
     * @param IntelligencePointNameService $intelligencePointNameService
     */
    public function __construct(IntelligencePointNameService $intelligencePointNameService)
    {
        $this->intelligencePointNameService = $intelligencePointNameService;
    }

    /**
     * Get intelligence point names as pagination.
     *
     * @OA\Get (
     *     path="/intelligence-point-names",
     *     summary="لیست نام های امتیاز هوش بصورت صفحه بندی",
     *     description="",
     *     tags={"نام های امتیاز هوش"},
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
        return $this->intelligencePointNameService->index($request);
    }

    /**
     * Get all intelligence point names.
     *
     * @OA\Get (
     *     path="/intelligence-point-names/all",
     *     summary="دریافت همه ی نام های امتیاز هوش",
     *     description="",
     *     tags={"نام های امتیاز هوش"},
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
    public function all(Request $request)
    {
        return $this->intelligencePointNameService->all($request);
    }

    /**
     * Store an intelligence point name.
     *
     * @OA\Post(
     *     path="/intelligence-point-names",
     *     summary="ثبت، نام های امتیاز هوش",
     *     description="",
     *     tags={"نام های امتیاز هوش"},
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
        return $this->intelligencePointNameService->store($request);
    }

    /**
     * Update an intelligence point name.
     *
     * @OA\Post(
     *     path="/intelligence-point-names/{id}",
     *     summary="بروزرسانی، نام های امتیاز هوش",
     *     description="",
     *     tags={"نام های امتیاز هوش"},
     *     @OA\Parameter(
     *         description="شناسه نام امتیاز هوش",
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
    public function update(Request $request, $intelligencePointName)
    {
        return $this->intelligencePointNameService->update($request,$intelligencePointName);
    }

    /**
     * Destroy an intelligence point name.
     *
     * @OA\Delete(
     *     path="/intelligence-point-names/{id}",
     *     summary="حذف، نام امتیاز هوش",
     *     description="",
     *     tags={"نام های امتیاز هوش"},
     *     @OA\Parameter(
     *         description="شناسه نام امتیاز هوش",
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
    public function destroy(Request $request,$intelligencePointName)
    {
        return $this->intelligencePointNameService->destroy($request,$intelligencePointName);
    }
}
