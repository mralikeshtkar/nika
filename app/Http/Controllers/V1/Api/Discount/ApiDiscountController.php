<?php

namespace App\Http\Controllers\V1\Api\Discount;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Discount\DiscountService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiDiscountController extends ApiBaseController
{
    /**
     * @var DiscountService
     */
    private DiscountService $discountService;

    /**
     * @param DiscountService $discountService
     */
    public function __construct(DiscountService $discountService)
    {
        $this->discountService = $discountService;
    }

    /**
     * @OA\Get (
     *     path="/discounts",
     *     summary="لیست تخفیف ها بصورت صفحه بندی",
     *     description="",
     *     tags={"تخفیف"},
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
        return $this->discountService->index($request);
    }

    /**
     * @OA\Get(
     *     path="/discounts/{id}",
     *     summary="دریافت اطلاعات تخفیف",
     *     description="",
     *     tags={"تخفیف"},
     *     @OA\Parameter(
     *         description="شناسه تخفیف",
     *         in="path",
     *         name="id",
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
    public function show(Request $request, $discount)
    {
        return $this->discountService->show($request, $discount);
    }

    /**
     * @OA\Post(
     *     path="/discounts",
     *     summary="ثبت تخفیف",
     *     description="",
     *     tags={"تخفیف"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"code","amount"},
     *                 @OA\Property(
     *                     property="code",
     *                     type="string",
     *                     description="کد"
     *                 ),
     *                 @OA\Property(
     *                     property="is_percent",
     *                     type="number",
     *                     description="آیا درصد است یا خیر (بصورت 0 یا 1)"
     *                 ),
     *                 @OA\Property(
     *                     property="amount",
     *                     type="string",
     *                     description="میزان تخفیف"
     *                 ),
     *                 @OA\Property(
     *                     property="enable_at",
     *                     type="string",
     *                     description="فعال بودن تخفیف از تاریخ - به فرمت Y/m/d H:i:s"
     *                 ),
     *                 @OA\Property(
     *                     property="expire_at",
     *                     type="string",
     *                     description="فعال بودن تخفیف تا تاریخ - به فرمت Y/m/d H:i:s"
     *                 ),
     *                 @OA\Property(
     *                     property="usage_limitation",
     *                     type="string",
     *                     description="محدودیت تعداد استفاده"
     *                 ),
     *                 @OA\Property(
     *                     property="status",
     *                     type="string",
     *                     description="وضعیت : Active,Inactive"
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
     * @throws Exception
     */
    public function store(Request $request)
    {
        return $this->discountService->store($request);
    }

    /**
     * @OA\Post(
     *     path="/discounts/{id}",
     *     summary="بروزرسانی تخفیف",
     *     description="",
     *     tags={"تخفیف"},
     *     @OA\Parameter(
     *         description="شناسه تخفیف",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"_method","code","amount"},
     *                 @OA\Property(
     *                     property="_method",
     *                     type="string",
     *                     default="put",
     *                     enum={"put"},
     *                     description="این مقدار باید بصورت ثابت شود",
     *                 ),
     *                 @OA\Property(
     *                     property="code",
     *                     type="string",
     *                     description="کد"
     *                 ),
     *                 @OA\Property(
     *                     property="is_percent",
     *                     type="number",
     *                     description="آیا درصد است یا خیر (بصورت 0 یا 1)"
     *                 ),
     *                 @OA\Property(
     *                     property="amount",
     *                     type="string",
     *                     description="میزان تخفیف"
     *                 ),
     *                 @OA\Property(
     *                     property="enable_at",
     *                     type="string",
     *                     description="فعال بودن تخفیف از تاریخ - به فرمت Y/m/d H:i:s"
     *                 ),
     *                 @OA\Property(
     *                     property="expire_at",
     *                     type="string",
     *                     description="فعال بودن تخفیف تا تاریخ - به فرمت Y/m/d H:i:s"
     *                 ),
     *                 @OA\Property(
     *                     property="usage_limitation",
     *                     type="string",
     *                     description="محدودیت تعداد استفاده"
     *                 ),
     *                 @OA\Property(
     *                     property="status",
     *                     type="string",
     *                     description="وضعیت : Active,Inactive"
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
     * @throws Exception
     */
    public function update(Request $request, $discount)
    {
        return $this->discountService->update($request, $discount);
    }

    /**
     * @OA\Delete (
     *     path="/discounts/{id}",
     *     summary="حذف تخفیف",
     *     description="",
     *     tags={"تخفیف"},
     *     @OA\Parameter(
     *         description="شناسه تخفیف",
     *         in="path",
     *         name="id",
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
    public function destroy(Request $request, $discount)
    {
        return $this->discountService->destroy($request, $discount);
    }
}
