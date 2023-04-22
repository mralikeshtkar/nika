<?php

namespace App\Http\Controllers\V1\Api\Product;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Product\ProductService;
use Illuminate\Http\Request;

class ApiProductController extends ApiBaseController
{
    /**
     * @var ProductService
     */
    private ProductService $productService;

    /**
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @OA\Get (
     *     path="/products",
     *     summary="دریافت محصولات بصورت صفحه بندی",
     *     description="",
     *     tags={"محصول"},
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
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function index(Request $request)
    {
        return $this->productService->index($request);
    }

    /**
     * @OA\Get (
     *     path="/products/all",
     *     summary="دریافت لیست محصولات",
     *     description="",
     *     tags={"محصول"},
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function all(Request $request)
    {
        return $this->productService->all($request);
    }

    /**
     * @OA\Get (
     *     path="/products/{id}",
     *     summary="نمایش اطلاعات یک محصول",
     *     description="",
     *     tags={"محصول"},
     *     @OA\Parameter(
     *         description="شناسه محصول",
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
    public function show(Request $request,$product)
    {
        return $this->productService->show($request,$product);
    }

    /**
     * @OA\Post(
     *     path="/products",
     *     summary="ثبت محصول",
     *     description="",
     *     tags={"محصول"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title","body","quantity"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="عنوان"
     *                 ),
     *                 @OA\Property(
     *                     property="body",
     *                     type="string",
     *                     description="متن"
     *                 ),
     *                 @OA\Property(
     *                     property="quantity",
     *                     type="string",
     *                     description="تعداد"
     *                 ),
     *                 @OA\Property(
     *                     property="status",
     *                     type="string",
     *                     description="وضعیت : Active, Inactive"
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
    public function store(Request $request)
    {
        return $this->productService->store($request);
    }

    /**
     * @OA\Post(
     *     path="/products/{id}",
     *     summary="بروزرسانی محصول",
     *     description="",
     *     tags={"محصول"},
     *     @OA\Parameter(
     *         description="شناسه محصول",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"_method"},
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
     *                     property="body",
     *                     type="string",
     *                     description="متن"
     *                 ),
     *                 @OA\Property(
     *                     property="quantity",
     *                     type="string",
     *                     description="تعداد"
     *                 ),
     *                 @OA\Property(
     *                     property="status",
     *                     type="string",
     *                     description="وضعیت : Active, Inactive"
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
    public function update(Request $request,$product)
    {
        return $this->productService->update($request,$product);
    }
}
