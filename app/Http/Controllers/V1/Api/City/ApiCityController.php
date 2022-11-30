<?php

namespace App\Http\Controllers\V1\Api\City;

use App\Http\Controllers\Controller;
use App\Services\V1\City\CityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ApiCityController extends Controller
{
    /**
     * @var CityService
     */
    private CityService $cityService;

    /**
     * ApiCityController constructor.
     *
     * @param CityService $cityService
     */
    public function __construct(CityService $cityService)
    {
        $this->cityService = $cityService;
    }

    /**
     * Get cities as pagination.
     *
     * @OA\Get (
     *     path="/cities",
     *     summary="لیست شهر ها بصورت صفحه بندی",
     *     description="",
     *     tags={"شهر"},
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
     *     @OA\Parameter(
     *         description="جستجوی نام استان",
     *         in="query",
     *         name="province",
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
        return $this->cityService->index($request);
    }

    /**
     * Store a city.
     *
     * @OA\Post(
     *     path="/cities",
     *     summary="ثبت شهر",
     *     description="",
     *     tags={"شهر"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name","province_id"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="نام"
     *                 ),
     *                 @OA\Property(
     *                     property="province_id",
     *                     type="number",
     *                     description="شناسه استان"
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
        return $this->cityService->store($request);
    }

    /**
     * Update a city.
     *
     * @OA\Post(
     *     path="/cities/{id}",
     *     summary="بروزرسانی شهر",
     *     description="",
     *     tags={"شهر"},
     *     @OA\Parameter(
     *         description="شناسه شهر",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"_method","name","province_id"},
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
     *                 @OA\Property(
     *                     property="province_id",
     *                     type="number",
     *                     description="شناسه استان"
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
    public function update(Request $request, $city)
    {
        return $this->cityService->update($request,$city);
    }

    /**
     * Delete a city.
     *
     * @OA\Delete(
     *     path="/cities/{id}",
     *     summary="حذف شهر",
     *     description="",
     *     tags={"شهر"},
     *     @OA\Parameter(
     *         description="شناسه شهر",
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
    public function destroy(Request $request, $city)
    {
        return $this->cityService->destroy($request,$city);
    }
}
