<?php

namespace App\Http\Controllers\V1\Api\Rahjoo;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Rahjoo\RahjooService;
use App\Services\V1\Rahjoo\RahjooSupportService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use OpenApi\Annotations as OA;

class ApiRahjooSupportController extends ApiBaseController
{
    /**
     * @var RahjooSupportService
     */
    private RahjooSupportService $rahjooSupportService;

    /**
     * @param RahjooSupportService $rahjooSupportService
     */
    public function __construct(RahjooSupportService $rahjooSupportService)
    {
        $this->rahjooSupportService = $rahjooSupportService;
    }

    /**
     * @OA\Get(
     *     path="/rahjoo-supports/{rahjooSupport}",
     *     summary="دریافت پشتیبانی رهجو",
     *     description="",
     *     tags={"پشتیبان"},
     *     @OA\Parameter(
     *         description="شناسه پشتیبانی رهجو",
     *         in="path",
     *         name="rahjooSupport",
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
    public function show(Request $request, $rahjooSupport)
    {
        return $this->rahjooSupportService->show($request, $rahjooSupport);
    }

    /**
     * @OA\Post(
     *     path="/rahjoo-supports/{rahjooSupport}",
     *     summary="بروزرسانی  پشتیبانی رهجو",
     *     description="",
     *     tags={"پشتیبان"},
     *     @OA\Parameter(
     *         description="شناسه پشتیبانی رهجو",
     *         in="path",
     *         name="rahjooSupport",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"_method","step"},
     *                 @OA\Property(
     *                     property="_method",
     *                     type="string",
     *                     default="put",
     *                     enum={"put"},
     *                     description="این مقدار باید بصورت ثابت شود",
     *                 ),
     *                 @OA\Property(
     *                     property="step",
     *                     type="string",
     *                     description="میتواند first second third باشد"
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
    public function update(Request $request, $rahjooSupport)
    {
        return $this->rahjooSupportService->update($request, $rahjooSupport);
    }

    /**
     * @OA\Post(
     *     path="/rahjoo-supports/{rahjooSupport}/cancel",
     *     summary="لغو  پشتیبانی رهجو",
     *     description="",
     *     tags={"پشتیبان"},
     *     @OA\Parameter(
     *         description="شناسه پشتیبانی رهجو",
     *         in="path",
     *         name="rahjooSupport",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"description"},
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="توضیحات"
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
    public function cancel(Request $request, $rahjooSupport)
    {
        return $this->rahjooSupportService->cancel($request, $rahjooSupport);
    }

    /**
     * @OA\Post(
     *     path="/rahjoo-supports/{rahjooSupport}/change-step",
     *     summary="تغییر مرحله  پشتیبانی رهجو",
     *     description="",
     *     tags={"پشتیبان"},
     *     @OA\Parameter(
     *         description="شناسه پشتیبانی رهجو",
     *         in="path",
     *         name="rahjooSupport",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"step"},
     *                 @OA\Property(
     *                     property="step",
     *                     type="string",
     *                     description="میتواند first - second - third باشد",
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
    public function changeStep(Request $request, $rahjooSupport)
    {
        return $this->rahjooSupportService->changeStep($request, $rahjooSupport);
    }

}
