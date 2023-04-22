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
     * Get a province with cities.
     *
     * @OA\Get(
     *     path="/discounts/{id}",
     *     summary="دریافت اطلاعات تخفیف",
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
    public function show(Request $request, $discount)
    {
        return $this->discountService->show($request, $discount);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(Request $request)
    {
        return $this->discountService->store($request);
    }

    /**
     * @param Request $request
     * @param $discount
     * @return JsonResponse
     * @throws Exception
     */
    public function update(Request $request, $discount)
    {
        return $this->discountService->update($request, $discount);
    }

    public function destroy(Request $request, $discount)
    {
        return $this->discountService->destroy($request, $discount);
    }
}
