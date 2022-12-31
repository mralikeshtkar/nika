<?php

namespace App\Http\Controllers\V1\Api\Question;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Question\QuestionPointService;
use Illuminate\Http\Request;

class ApiQuestionPointController extends ApiBaseController
{
    /**
     * @var QuestionPointService
     */
    private QuestionPointService $questionPointService;

    /**
     * @param QuestionPointService $questionPointService
     */
    public function __construct(QuestionPointService $questionPointService)
    {
        $this->questionPointService = $questionPointService;
    }

    public function store(Request $request, $question)
    {
        return $this->questionPointService->store($request, $question);
    }
}
