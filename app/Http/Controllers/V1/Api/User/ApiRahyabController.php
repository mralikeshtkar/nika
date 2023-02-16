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

    /**
     * @OA\Get(
     *     path="/rahyab/{rahyab}/{rahjoo}/exercises",
     *     summary="دریافت تمرین های رهجو رهیاب",
     *     description="",
     *     tags={"رهیاب"},
     *     @OA\Parameter(
     *         description="شناسه رهیاب",
     *         in="path",
     *         name="rahyab",
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
     *     @OA\Parameter(
     *         description="تمرین هایی که پاسخ داده شده اند. مقدار مهم نیست ولی 1بزارید",
     *         in="query",
     *         name="answered",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Parameter(
     *         description="تمرین هایی که پاسخ داده نشده اند. مقدار مهم نیست ولی 1بزارید",
     *         in="query",
     *         name="notAnswered",
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
    public function exercises(Request $request, $rahyab, $rahjoo)
    {
        return $this->rahyabService->exercises($request, $rahyab, $rahjoo);
    }

    /**
     * @OA\Get(
     *     path="/rahyab/{rahyab}/{rahjoo}/exercises/{exercise}/questions",
     *     summary="دریافت سوال های رهجو رهیاب",
     *     description="",
     *     tags={"رهیاب"},
     *     @OA\Parameter(
     *         description="شناسه رهیاب",
     *         in="path",
     *         name="rahyab",
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
     *     @OA\Parameter(
     *         description="شناسه تمرین",
     *         in="path",
     *         name="exercise",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Parameter(
     *         description="تمرین هایی که پاسخ داده شده اند. مقدار مهم نیست ولی 1بزارید",
     *         in="query",
     *         name="answered",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Parameter(
     *         description="تمرین هایی که پاسخ داده نشده اند. مقدار مهم نیست ولی 1بزارید",
     *         in="query",
     *         name="notAnswered",
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
    public function questions(Request $request, $rahyab, $rahjoo, $exercise)
    {
        return $this->rahyabService->questions($request, $rahyab, $rahjoo, $exercise);
    }

    /**
     * @OA\Get(
     *     path="/rahyab/{rahyab}/{rahjoo}/exercises/{exercise}/questions/{question}",
     *     summary="دریافت سوال رهجو رهیاب",
     *     description="",
     *     tags={"رهیاب"},
     *     @OA\Parameter(
     *         description="شناسه رهیاب",
     *         in="path",
     *         name="rahyab",
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
     *     @OA\Parameter(
     *         description="شناسه تمرین",
     *         in="path",
     *         name="exercise",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Parameter(
     *         description="شناسه سوال",
     *         in="path",
     *         name="question",
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
    public function question(Request $request, $rahyab, $rahjoo, $exercise,$question)
    {
        return $this->rahyabService->question($request, $rahyab, $rahjoo, $exercise,$question);
    }
}
