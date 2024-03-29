<?php

namespace App\Http\Controllers\V1\Api\Order;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Order\OrderService;
use Illuminate\Http\Request;

class ApiOrderController extends ApiBaseController
{
    /**
     * @var OrderService
     */
    private OrderService $orderService;

    /**
     * @param OrderService $orderService
     */
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * @OA\Get (
     *     path="/orders",
     *     summary="لیست سفارشات بصورت صفحه بندی",
     *     description="",
     *     tags={"سفارشات"},
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
     *         description="وضعیت: Preparation,Posted,Delivered",
     *         in="query",
     *         name="status",
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Parameter(
     *         description="شناسه رهجو",
     *         in="query",
     *         name="rahjoo_id",
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات با موفقیت انجام شد",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function index(Request $request)
    {
        return $this->orderService->index($request);
    }

    /**
     * @OA\Get (
     *     path="/orders/{id}",
     *     summary="نمایش اطلاعات یک سفارش",
     *     description="",
     *     tags={"سفارشات"},
     *     @OA\Parameter(
     *         description="شناسه کاربر",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات با موفقیت انجام شد",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function show(Request $request, $order)
    {
        return $this->orderService->show($request,$order);
    }

    /**
     * @OA\Post(
     *     path="/orders/{id}",
     *     summary="بروزرسانی سفارش",
     *     description="",
     *     tags={"سفارشات"},
     *     @OA\Parameter(
     *         description="شناسه کاربر",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"_method","tracking_code","sent_at"},
     *                 @OA\Property(
     *                     property="_method",
     *                     type="string",
     *                     default="put",
     *                     enum={"put"},
     *                     description="این مقدار باید بصورت ثابت شود",
     *                 ),
     *                 @OA\Property(
     *                     property="tracking_code",
     *                     type="string",
     *                     description="کد رهگیری"
     *                 ),
     *                 @OA\Property(
     *                     property="sent_at",
     *                     type="string",
     *                     description="تاریخ تولد بصورت شمسی با فرمت Y/m/d H:i:s "
     *                 ),
     *                 @OA\Property(
     *                     property="status",
     *                     type="string",
     *                     description="میتواند preparation, posted, delivered باشد"
     *                 ),
     *                  @OA\Property(
     *                      property="file",
     *                      type="string",
     *                      description="رسید پست",
     *                      format="binary",
     *                  ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات با موفقیت انجام شد",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function update(Request $request, $order)
    {
        return $this->orderService->update($request, $order);
    }
}
