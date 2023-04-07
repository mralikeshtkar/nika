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
     * @OA\Post(
     *     path="/requets-supports",
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
}
