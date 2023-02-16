<?php

namespace App\Http\Controllers\V1\Api\User;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\User\RahnamaService;
use Illuminate\Http\Request;

class ApiRahnamaController extends ApiBaseController
{
    /**
     * @var RahnamaService
     */
    private RahnamaService $rahnamaService;

    /**
     * @param RahnamaService $rahnamaService
     */
    public function __construct(RahnamaService $rahnamaService)
    {
        $this->rahnamaService = $rahnamaService;
    }

    /**
     * @OA\Get(
     *     path="/rahnama/{rahnama}/packages",
     *     summary="دریافت رهجو ها همراه با پکیج و تمرین",
     *     description="",
     *     tags={"رهنما"},
     *     @OA\Parameter(
     *         description="شناسه رهنما",
     *         in="path",
     *         name="rahnama",
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
        return $this->rahnamaService->packages($request, $rahyab);
    }
}
