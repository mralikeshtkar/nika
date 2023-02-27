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
    public function packages(Request $request, $rahnama)
    {
        return $this->rahnamaService->packages($request, $rahnama);
    }

    /**
     * @OA\Get(
     *     path="/rahnama/{rahnama}/{rahjoo}/packages",
     *     summary="دریافت تمرین های رهجو رهنما",
     *     description="",
     *     tags={"رهنما"},
     *     @OA\Parameter(
     *         description="شناسه رهنما",
     *         in="path",
     *         name="rahnama",
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
    public function exercises(Request $request, $rahnama, $rahjoo)
    {
        return $this->rahnamaService->exercises($request, $rahnama, $rahjoo);
    }

    /**
     * @OA\Get(
     *     path="/rahnama/{rahnama}/{rahjoo}/exercises/{exercise}/questions",
     *     summary="دریافت سوال های رهجو رهنما",
     *     description="",
     *     tags={"رهنما"},
     *     @OA\Parameter(
     *         description="شناسه رهنما",
     *         in="path",
     *         name="rahnama",
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
    public function questions(Request $request, $rahnama, $rahjoo, $exercise)
    {
        return $this->rahnamaService->questions($request, $rahnama, $rahjoo, $exercise);
    }

    /**
     * @OA\Get(
     *     path="/rahnama/{rahnama}/{rahjoo}/exercises/{exercise}/questions/{question}",
     *     summary="دریافت سوال رهجو رهنما",
     *     description="",
     *     tags={"رهنما"},
     *     @OA\Parameter(
     *         description="شناسه رهنما",
     *         in="path",
     *         name="rahnama",
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
    public function question(Request $request, $rahnama, $rahjoo, $exercise, $question)
    {
        return $this->rahnamaService->question($request, $rahnama, $rahjoo, $exercise, $question);
    }
}
