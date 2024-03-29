<?php

namespace App\Http\Controllers\V1\Api\User;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\User\SupportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ApiSupportController extends ApiBaseController
{
    /**
     * @var SupportService
     */
    private SupportService $supportService;

    /**
     * @param SupportService $supportService
     */
    public function __construct(SupportService $supportService)
    {
        $this->supportService = $supportService;
    }

    /**
     * @OA\Get(
     *     path="/support/{support}/rahjoos",
     *     summary="دریافت رهجوهای پشتیبان",
     *     description="",
     *     tags={"پشتیبان"},
     *     @OA\Parameter(
     *         description="شناسه پشتیبانی",
     *         in="path",
     *         name="support",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Parameter(
     *         description="گام",
     *         in="query",
     *         name="step",
     *         description="میتواند first - second - third باشد",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Parameter(
     *         description="سفارش های در حال اماده سازی",
     *         in="query",
     *         name="preparation",
     *         description="مقدار 1",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Parameter(
     *         description="سفارش های که به پست تحویل داده شده اند",
     *         in="query",
     *         name="posted",
     *         description="مقدار 1",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Parameter(
     *         description="سفارش های که به خریدار تحویل داده شده است",
     *         in="query",
     *         name="delivered",
     *         description="مقدار 1",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Parameter(
     *         description="لغو شده ها",
     *         in="query",
     *         name="canceled",
     *         description="مقدار 1",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent()
     *     )
     * )
     */
    public function rahjoos(Request $request,$support)
    {
        return $this->supportService->rahjoos($request,$support);
    }

    /**
     * @OA\Get(
     *     path="/support/{support}/rahjoos/{rahjoo}",
     *     summary="دریافت رهجو پشتیبان",
     *     description="",
     *     tags={"پشتیبان"},
     *     @OA\Parameter(
     *         description="شناسه پشتیبانی",
     *         in="path",
     *         name="support",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Parameter(
     *         description="شناسه رهجو",
     *         in="path",
     *         name="rahjoo",
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
    public function rahjoo(Request $request,$support,$rahjoo)
    {
        return $this->supportService->rahjoo($request,$support,$rahjoo);
    }
}
