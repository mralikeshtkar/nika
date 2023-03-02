<?php

namespace App\Http\Controllers\V1\Api\Rahjoo;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Rahjoo\RahjooService;
use App\Services\V1\Rahjoo\RahjooSupportService;
use Illuminate\Http\Request;
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

}
