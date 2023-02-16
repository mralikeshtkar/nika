<?php

namespace App\Http\Controllers\V1\Api\User;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\User\RahnamaService;
use App\Services\V1\User\RahyabService;
use Illuminate\Http\Request;

class ApiRahyabController extends ApiBaseController
{
    /**
     * @var RahyabService
     */
    private RahyabService $rahyabService;

    /**
     * @param RahyabService $rahyabService
     */
    public function __construct(RahyabService $rahyabService)
    {
        $this->rahyabService = $rahyabService;
    }

    /**
     * @OA\Get(
     *     path="/rahyab/{rahyab}/packages",
     *     summary="دریافت رهجو ها همراه با پکیج و تمرین",
     *     description="",
     *     tags={"رهیاب"},
     *     @OA\Parameter(
     *         description="شناسه رهیاب",
     *         in="path",
     *         name="rahyab",
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
    public function packages(Request $request, $rahyab)
    {
        return $this->rahyabService->packages($request, $rahyab);
    }
}
