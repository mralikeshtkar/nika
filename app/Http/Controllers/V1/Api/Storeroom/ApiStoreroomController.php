<?php

namespace App\Http\Controllers\V1\Api\Storeroom;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Storeroom\StoreroomService;
use Illuminate\Http\Request;

class ApiStoreroomController extends ApiBaseController
{
    /**
     * @var StoreroomService
     */
    private StoreroomService $storeroomService;

    /**
     * @param StoreroomService $storeroomService
     */
    public function __construct(StoreroomService $storeroomService)
    {
        $this->storeroomService = $storeroomService;
    }

    /**
     * @OA\Get (
     *     path="/storerooms",
     *     summary="لیست انبار بصورت صفحه بندی",
     *     description="",
     *     tags={"انبار"},
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
        return $this->storeroomService->index($request);
    }

    /**
     * @OA\Get(
     *     path="/storerooms/{package}",
     *     summary="دریافت یک پکیج موجود در انبار",
     *     description="",
     *     tags={"انبار"},
     *     @OA\Parameter(
     *         description="شناسه پکیج",
     *         in="path",
     *         name="package",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent()
     *     )
     * )
     */
    public function show(Request $request, $package)
    {
        return $this->storeroomService->show($request,$package);
    }

    /**
     * @OA\Post(
     *     path="/storerooms/{package}/update-quantity",
     *     summary="بروزرسانی تعداد موجودی",
     *     description="",
     *     tags={"انبار"},
     *     @OA\Parameter(
     *         description="شناسه پکیج",
     *         in="path",
     *         name="package",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"quantity"},
     *                 @OA\Property(
     *                     property="first_name",
     *                     type="string",
     *                     description="تعداد"
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
    public function updateQuantity(Request $request, $package)
    {
        return $this->storeroomService->updateQuantity($request,$package);
    }
}
