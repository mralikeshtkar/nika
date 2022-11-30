<?php

namespace App\Http\Controllers\V1\Api\Province;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Http\Resources\V1\Province\ProvinceResource;
use App\Repositories\V1\Province\Interfaces\ProvinceRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\Province\ProvinceService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use function trans;

class ApiProvinceController extends ApiBaseController
{
    /**
     * @var ProvinceService
     */
    private ProvinceService $provinceService;

    /**
     * ProvinceController constructor.
     *
     * @param ProvinceService $provinceService
     */
    public function __construct(ProvinceService $provinceService)
    {
        $this->provinceService = $provinceService;
    }

    /**
     * Get provinces as pagination.
     *
     * @OA\Get (
     *     path="/provinces",
     *     summary="لیست استان ها بصورت صفحه بندی",
     *     description="",
     *     tags={"استان"},
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
        return $this->provinceService->index($request);
    }

    /**
     * Get all provinces.
     *
     * @OA\Get(
     *     path="/provinces/all",
     *     summary="استان‌ها",
     *     description="دریافت لیست همه استان‌ها",
     *     tags={"استان"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent()
     *     )
     * )
     */
    public function all(Request $request)
    {
        return $this->provinceService->all($request);
    }

    /**
     * Get a province with cities.
     *
     * @OA\Get(
     *     path="/provinces/{id}",
     *     summary="استان به همراه شهرها",
     *     description="دریافت استان به همراه شهرها",
     *     tags={"استان"},
     *     @OA\Parameter(
     *         description="شناسه استان",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="شناسه استان معتبر نمیباشد",
     *         @OA\JsonContent()
     *     )
     * )
     */
    public function show(Request $request,$province)
    {
        return $this->provinceService->show($request,$province);
    }

    /**
     * Store a province.
     *
     * @OA\Post(
     *     path="/provinces",
     *     summary="ثبت استان",
     *     description="",
     *     tags={"استان"},
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
        return $this->provinceService->store($request);
    }

    /**
     * Update a province.
     *
     * @OA\Post(
     *     path="/provinces/{id}",
     *     summary="بروزرسانی استان",
     *     description="",
     *     tags={"استان"},
     *     @OA\Parameter(
     *         description="شناسه استان",
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
    public function update(Request $request, $province)
    {
        return $this->provinceService->update($request,$province);
    }

    /**
     * Delete a province.
     *
     * @OA\Delete(
     *     path="/provinces/{id}",
     *     summary="حذف استان",
     *     description="",
     *     tags={"استان"},
     *     @OA\Parameter(
     *         description="شناسه استان",
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
    public function destroy(Request $request, $province)
    {
        return $this->provinceService->destroy($request,$province);
    }
}
