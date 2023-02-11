<?php

namespace App\Http\Controllers\V1\Api\Question;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Question\QuestionDurationService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ApiQuestionDurationController extends ApiBaseController
{
    /**
     * @var QuestionDurationService
     */
    private QuestionDurationService $questionDurationService;

    /**
     * @param QuestionDurationService $questionDurationService
     */
    public function __construct(QuestionDurationService $questionDurationService)
    {
        $this->questionDurationService = $questionDurationService;
    }

    /**
     * @OA\Post(
     *     path="/question-durations/{question}/start",
     *     summary="ثبت زمان پاسخ به سوال",
     *     description="",
     *     tags={"زمان پاسخ بهسوال"},
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
    public function start(Request $request,$question)
    {
        return $this->questionDurationService->start($request,$question);
    }
}
