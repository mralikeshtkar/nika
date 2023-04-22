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

    public function show(Request $request, $order)
    {
        return $this->orderService->show($request);
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
     *                 )
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
