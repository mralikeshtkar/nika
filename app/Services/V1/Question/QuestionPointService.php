<?php

namespace App\Services\V1\Question;

use App\Models\IntelligencePoint;
use App\Repositories\V1\Question\Interfaces\QuestionRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class QuestionPointService extends BaseService
{
    /**
     * @var QuestionRepositoryInterface
     */
    private QuestionRepositoryInterface $questionRepository;

    #region Constructor

    /**
     * @param QuestionRepositoryInterface $questionRepository
     */
    public function __construct(QuestionRepositoryInterface $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    #endregion

    #region Public methods

    public function store(Request $request, $question)
    {
        $question = $this->questionRepository->select(['id', 'exercise_id'])
            ->with(['pivotPoints:question_id,intelligence_point_id'])
            ->withOrderExercisePointsSum()
            ->withIntelligencePoints()
            ->findOrFailById($question);
        if ($question->pivotPoints->contains('intelligence_point_id', $request->intelligence_point_id))
            return ApiResponse::message(trans("The point has already been recorded for this question"), Response::HTTP_BAD_REQUEST)->send();
        $intelligencePoints = $question->intelligencePoints;
        $intelligencePoint = $intelligencePoints->firstWhere('id', $request->intelligence_point_id);
        $exercisePivotPoint = $question->exercisePivotPoints->firstWhere('intelligence_point_id', optional($intelligencePoint)->id);
        ApiResponse::validate($request->all(), [
            'intelligence_point_id' => ['required', Rule::in($intelligencePoints->pluck('id')->toArray())],
            'max_point' => collect(['required', 'numeric', 'min:1'])->when($intelligencePoint, function (Collection $collection) use ($intelligencePoint, $exercisePivotPoint) {
                $collection->push('max:' . optional($intelligencePoint)->max_point - intval(optional($exercisePivotPoint)->max_point_sum));
            }),
            'description' => ['nullable', 'string'],
        ]);
        $this->questionRepository->attachPoints($question, [
            $request->intelligence_point_id => ['max_point' => $request->max_point, 'description' => $request->description],
        ]);
        return ApiResponse::message(trans("The information was register successfully"), Response::HTTP_CREATED)->send();
    }

    #endregion
}
