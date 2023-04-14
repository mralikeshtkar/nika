<?php

namespace App\Http\Controllers\V1\Api\RequestSupport;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\RequestSupport\RequestSupportService;
use Illuminate\Http\Request;

class ApiRequestSupportController extends ApiBaseController
{
    /**
     * @var RequestSupportService
     */
    private RequestSupportService $requestSupportService;

    /**
     * @param RequestSupportService $requestSupportService
     */
    public function __construct(RequestSupportService $requestSupportService)
    {
        $this->requestSupportService = $requestSupportService;
    }

    /**
     * @OA\Get(
     *     path="/request-supports",
     *     summary="لیست درخواست های پشتیبانی",
     *     description="",
     *     tags={"درخواست پشتیبانی"},
     *     @OA\Parameter(
     *         description="مقدار 0 یا 1 ارسال شود بررسی شده",
     *         in="query",
     *         name="confirmed",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Parameter(
     *         description="نام",
     *         in="query",
     *         name="name",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Parameter(
     *         description="موبایل",
     *         in="query",
     *         name="mobile",
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
    public function index(Request $request)
    {
        return $this->requestSupportService->index($request);
    }

    /**
     * @OA\Post(
     *     path="/request-supports",
     *     summary="ثبت درخواست پشتیبانی",
     *     description="",
     *     tags={"درخواست پشتیبانی"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"mobile","first_name","last_name","birthdate"},
     *                 @OA\Property(
     *                     property="first_name",
     *                     type="string",
     *                     description="نام"
     *                 ),
     *                 @OA\Property(
     *                     property="last_name",
     *                     type="string",
     *                     description="نام خانوادگی"
     *                 ),
     *                 @OA\Property(
     *                     property="mobile",
     *                     type="string",
     *                     description="شماره موبایل"
     *                 ),
     *                 @OA\Property(
     *                     property="birthdate",
     *                     type="string",
     *                     description="تاریخ تولد بصورت شمسی با فرمت Y/m/d - مثال:1401/05/10"
     *                 )
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
        return $this->requestSupportService->store($request);
    }

    /**
     * @OA\Get (
     *     path="/request-supports/{requetSupport}",
     *     summary="نمایش درخواست پشتیبانی",
     *     description="",
     *     tags={"درخواست پشتیبانی"},
     *     @OA\Parameter(
     *         description="شناسه درخواست پشتیبانی",
     *         in="path",
     *         name="requetSupport",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="موفق",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function show(Request $request, $requestSupport)
    {
        return $this->requestSupportService->show($request,$requestSupport);
    }

    /**
     * @OA\Post(
     *     path="/request-supports/{requetSupport}/confirm",
     *     summary="تایید دیدن درخواست پشتیبانی",
     *     description="",
     *     tags={"درخواست پشتیبانی"},
     *     @OA\Parameter(
     *         description="شناسه درخواست پشتیبانی",
     *         in="path",
     *         name="requetSupport",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="ثبت با موفقیت انجام شد",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function confirm(Request $request, $requestSupport)
    {
        return $this->requestSupportService->confirm($request,$requestSupport);
    }
}
