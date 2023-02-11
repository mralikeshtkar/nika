<?php

namespace App\Services\V1\Question;

use App\Repositories\V1\Exercise\Interfaces\ExerciseRepositoryInterfaces;
use App\Repositories\V1\Package\Interfaces\PackageRepositoryInterface;
use App\Repositories\V1\Question\Interfaces\QuestionDurationRepositoryInterface;
use App\Repositories\V1\Rahjoo\Interfaces\RahjooRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class QuestionDurationService extends BaseService
{
    /**
     * @var QuestionDurationRepositoryInterface
     */
    private QuestionDurationRepositoryInterface $questionDurationRepository;

    #region Constructor

    /**
     * @param QuestionDurationRepositoryInterface $questionDurationRepository
     */
    public function __construct(QuestionDurationRepositoryInterface $questionDurationRepository)
    {
        $this->questionDurationRepository = $questionDurationRepository;
    }

    #endregion

    #region Public methods

    /**
     * @param Request $request
     * @param $question
     * @return JsonResponse
     */
    public function start(Request $request, $question): JsonResponse
    {
        $rahjoo = $request->user()->rahjoo()->select(['id'])->first();
        abort_if(!$rahjoo, ApiResponse::error(trans("The rahjoo can start answering the questions"), Response::HTTP_BAD_REQUEST)->send());
        $question = resolve(ExerciseRepositoryInterfaces::class)->select(['id'])->findOrFailById($question);
        $this->questionDurationRepository->create([
            'rahjoo_id' => $rahjoo->id,
            'question_id' => $question->id,
            'start' => now(),
        ]);
        return ApiResponse::message(trans("Mission accomplished"))->send();
    }

    #endregion

}
