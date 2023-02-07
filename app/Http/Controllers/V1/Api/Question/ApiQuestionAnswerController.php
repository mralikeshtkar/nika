<?php

namespace App\Http\Controllers\V1\Api\Question;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Question\QuestionAnswerService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ApiQuestionAnswerController extends ApiBaseController
{
    /**
     * @var QuestionAnswerService
     */
    private QuestionAnswerService $questionAnswerService;

    /**
     * @param QuestionAnswerService $questionAnswerService
     */
    public function __construct(QuestionAnswerService $questionAnswerService)
    {
        $this->questionAnswerService = $questionAnswerService;
    }

    /**
     * @OA\Post(
     *     path="/rahjoos/{rahjoo}/exercise/{exercise}/questions",
     *     summary="پاسخ سوال",
     *     description="",
     *     tags={"پاسخ سوال"},
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
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               @OA\Property(
     *                   property="question_id",
     *                   type="string",
     *                   description="شناسه سوال"
     *               ),
     *               @OA\Property(
     *                  property="answers",
     *                  type="array",
     *                  @OA\Items(
     *                       type="string",
     *                       format="binary",
     *                  ),
     *               ),
     *           ),
     *       )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="ثبت با موفقیت انجام شد",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function store(Request $request, $rahjoo, $exercise)
    {
        return $this->questionAnswerService->store($request, $rahjoo, $exercise);
    }

    /**
     * @OA\Post(
     *     path="/rahjoos/{rahjoo}/exercise/{exercise}/question-single",
     *     summary="بصورت تکی پاسخ سوال",
     *     description="",
     *     tags={"پاسخ سوال"},
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
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               @OA\Property(
     *                   property="question_id",
     *                   type="string",
     *                   description="شناسه سوال"
     *               ),
     *               @OA\Property(
     *                   property="answer_type_id",
     *                   type="string",
     *                   description="شناسه نوع جواب سوال"
     *               ),
     *               @OA\Property(
     *                   property="file",
     *                   type="string",
     *                   description="یا باید فایل باید یا متن (در swagger چنین قابلیتی وجود ندارد برای تست بزارم یا متن باشه یا فایل)"
     *               ),
     *           ),
     *       )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="ثبت با موفقیت انجام شد",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function storeSingle(Request $request,$rahjoo, $exercise)
    {
        return $this->questionAnswerService->storeSingle($request, $rahjoo, $exercise);
    }

    /**
     * @OA\Get (
     *     path="/rahjoos/{rahjoo}/exercise/{exercise}/questions/{question}",
     *     summary="نمایش سوال همراه با پاسخ",
     *     description="",
     *     tags={"رهجو"},
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
     *         description="عملیات موفق",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function showQuestionWithAnswer(Request $request, $rahjoo, $exercise, $question)
    {
        return $this->questionAnswerService->showQuestionWithAnswer($request, $rahjoo, $exercise,$question);
    }
}
