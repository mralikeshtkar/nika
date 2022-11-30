<?php

namespace App\Http\Controllers\V1\Api\Major;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Major\MajorService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ApiMajorController extends ApiBaseController
{
    /**
     * @var MajorService
     */
    private MajorService $majorService;

    /**
     * @param MajorService $majorService
     */
    public function __construct(MajorService $majorService)
    {
        $this->majorService = $majorService;
    }

    /**
     * Get all majors as pagination.
     *
     * @OA\Get (
     *     path="/majors",
     *     summary="لیست رشته های تحصیلی بصورت صفحه بندی",
     *     description="",
     *     tags={"رشته تحصیلی"},
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
     *         @OA\Schema(type="srting"),
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
        return $this->majorService->index($request);
    }

    /**
     * Get all majors as pagination.
     *
     * @OA\Get (
     *     path="/majors/all",
     *     summary="دریافت لیست رشته های تحصیلی",
     *     description="",
     *     tags={"رشته تحصیلی"},
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function all(Request $request)
    {
        return $this->majorService->all($request);
    }

    /**
     * Store a Major.
     *
     * @OA\Post(
     *     path="/majors",
     *     summary="ثبت رشته تحصیلی",
     *     description="",
     *     tags={"رشته تحصیلی"},
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
        return $this->majorService->store($request);
    }

    /**
     * Update a major.
     *
     * @OA\Post(
     *     path="/majors/{id}",
     *     summary="بروزرسانی رشته تحصیلی",
     *     description="",
     *     tags={"رشته تحصیلی"},
     *     @OA\Parameter(
     *         description="شناسه رشته تحصیلی",
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
    public function update(Request $request, $major)
    {
        return $this->majorService->update($request,$major);
    }

    /**
     * Delete a major.
     *
     * @OA\Delete(
     *     path="/majors/{id}",
     *     summary="حذف رشته تحصیلی",
     *     description="",
     *     tags={"رشته تحصیلی"},
     *     @OA\Parameter(
     *         description="شناسه رشته تحصیلی",
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
    public function destroy(Request $request, $major)
    {
        return $this->majorService->destroy($request,$major);
    }
}
