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

    public function showQuestionWithAnswers(Request $request, $rahjoo, $exercise, $question)
    {
        return $this->questionAnswerService->showQuestionWithAnswers($request, $rahjoo, $exercise,$question);
    }
}
