<?php

namespace App\Services\V1\Question;

use App\Http\Resources\V1\IntelligencePoint\IntelligencePointResource;
use App\Models\IntelligencePoint;
use App\Models\Question;
use App\Repositories\V1\IntelligencePoint\Interfaces\IntelligencePointRepositoryInterface;
use App\Repositories\V1\Package\Interfaces\IntelligencePackageRepositoryInterface;
use App\Repositories\V1\Question\Interfaces\QuestionRepositoryInterface;
use App\Repositories\V1\Rahjoo\Interfaces\RahjooRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
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

    /**
     * @param Request $request
     * @param $question
     * @return JsonResponse
     */
    public function index(Request $request, $question): JsonResponse
    {
        $question = $this->questionRepository->with(['points' => function ($q) {
            $q->select(['id', 'user_id', 'intelligence_package_id', 'intelligence_point_name_id', 'intelligence_points.max_point'])->withPointName();
        }])->select(['id', 'user_id'])->findOrFailById($question);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('points', IntelligencePointResource::collection($question->points))
            ->send();
    }

    /**
     * @param Request $request
     * @param $question
     * @param $rahjoo
     * @return JsonResponse
     */
    public function haveNotPoint(Request $request, $question, $rahjoo): JsonResponse
    {
        $rahjoo = resolve(RahjooRepositoryInterface::class)->select(['id', 'package_id'])->findorFailById($rahjoo);
        /** @var Question $question */
        $question = $this->questionRepository->query($rahjoo->questions())->findOrFailById($question);
        $points = $question->points()
            ->withPointName()
            ->whereDoesntHave('questionPointRahjoo', function ($q) use ($rahjoo, $question) {
                $q->where('question_id', $question->id)
                    ->where('rahjoo_id', $rahjoo->id);
            })->get();
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('points', IntelligencePointResource::collection($points))
            ->send();
    }

    /**
     * @param Request $request
     * @param $question
     * @return JsonResponse
     */
    public function store(Request $request, $question): JsonResponse
    {
        $question = $this->questionRepository->select(['id', 'exercise_id'])
            ->with(['pivotPoints:question_id,intelligence_point_id'])
            ->withOrderExercisePointsSum()
            ->findOrFailById($question);
        if ($question->pivotPoints->contains('intelligence_point_id', $request->intelligence_point_id))
            return ApiResponse::message(trans("The point has already been recorded for this question"), Response::HTTP_BAD_REQUEST)->send();
        $intelligencePoint = resolve(IntelligencePointRepositoryInterface::class)->findById($request->intelligence_point_id);
        $exercisePivotPoint = $question->exercisePivotPoints->firstWhere('intelligence_point_id', optional($intelligencePoint)->id);
        ApiResponse::validate($request->all(), [
            'intelligence_point_id' => ['required', 'exists:' . IntelligencePoint::class . ',id'],
            'max_point' => collect(['required', 'numeric', 'min:1'])->when($intelligencePoint, function (Collection $collection) use ($intelligencePoint, $exercisePivotPoint) {
                $collection->push('max:' . optional($intelligencePoint)->max_point - intval(optional($exercisePivotPoint)->max_point_sum));
            }),
            'description' => ['nullable', 'string'],
        ]);
        $this->questionRepository->attachPoints($question, [
            $request->intelligence_point_id => ['max_point' => $request->max_point, 'description' => $request->description],
        ]);
        return ApiResponse::message(trans("The information was register successfully"), Response::HTTP_CREATED)
            ->addData('description', $request->description)
            ->addData('max_point', $request->max_point)
            ->send();
    }

    /**
     * @param Request $request
     * @param $question
     * @return JsonResponse
     */
    public function update(Request $request, $question): JsonResponse
    {
        $question = $this->questionRepository->select(['id', 'exercise_id'])
            ->with(['pivotPoints:question_id,intelligence_point_id'])
            ->withOrderExercisePointsSum()
            ->findOrFailById($question);
        if (!$question->pivotPoints->contains('intelligence_point_id', $request->intelligence_point_id))
            return ApiResponse::message(trans("No point has been recorded for this question"), Response::HTTP_BAD_REQUEST)->send();
        $intelligencePoint = resolve(IntelligencePointRepositoryInterface::class)->findById($request->intelligence_point_id);
        $exercisePivotPoint = $question->exercisePivotPoints->firstWhere('intelligence_point_id', optional($intelligencePoint)->id);
        ApiResponse::validate($request->all(), [
            'intelligence_point_id' => ['required', 'exists:' . IntelligencePoint::class . ',id'],
            'max_point' => collect(['required', 'numeric', 'min:1'])->when($intelligencePoint, function (Collection $collection) use ($intelligencePoint, $exercisePivotPoint) {
                $collection->push('max:' . optional($intelligencePoint)->max_point + intval(optional($exercisePivotPoint)->max_point_sum));
            }),
            'description' => ['nullable', 'string'],
        ]);
        $this->questionRepository->updatePoint($question, $request->intelligence_point_id, [
            'max_point' => $request->max_point,
            'description' => $request->description,
        ]);
        return ApiResponse::message(trans("Mission accomplished"))
            ->addData('description', $request->description)
            ->addData('max_point', $request->max_point)
            ->send();
    }

    /**
     * @param Request $request
     * @param $question
     * @return JsonResponse
     */
    public function destroy(Request $request, $question): JsonResponse
    {
        ApiResponse::validate($request->all(), [
            'intelligence_point_id' => ['required', 'exists:' . IntelligencePoint::class . ',id']
        ]);
        $question = $this->questionRepository->select(['id', 'exercise_id'])
            ->with(['pivotPoints:question_id,intelligence_point_id'])
            ->findOrFailById($question);
        if (!$question->pivotPoints->contains('intelligence_point_id', $request->intelligence_point_id))
            return ApiResponse::message(trans("No point has been recorded for this question"), Response::HTTP_BAD_REQUEST)->send();
        $this->questionRepository->detachPoints($question, [$request->intelligence_point_id]);
        return ApiResponse::message(trans("Mission accomplished"))->send();
    }

    #endregion
}
