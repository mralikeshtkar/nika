<?php

namespace App\Services\V1\Question;

use App\Enums\Question\QuestionAnswerType;
use App\Http\Resources\V1\Question\QuestionAnswerTypeResource;
use App\Repositories\V1\Question\Interfaces\QuestionAnswerTypeServiceRepositoryInterface;
use App\Repositories\V1\Question\Interfaces\QuestionRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class QuestionAnswerTypeService extends BaseService
{
    /**
     * @var QuestionAnswerTypeServiceRepositoryInterface
     */
    private QuestionAnswerTypeServiceRepositoryInterface $questionAnswerTypeServiceRepository;

    #region Constructor

    /**
     * @param QuestionAnswerTypeServiceRepositoryInterface $questionAnswerTypeServiceRepository
     */
    public function __construct(QuestionAnswerTypeServiceRepositoryInterface $questionAnswerTypeServiceRepository)
    {
        $this->questionAnswerTypeServiceRepository = $questionAnswerTypeServiceRepository;
    }

    #endregion

    #region Public methods

    /**
     * @param Request $request
     * @param $question
     * @return JsonResponse
     */
    public function store(Request $request, $question): JsonResponse
    {
        $questionRepository = resolve(QuestionRepositoryInterface::class);
        $question = $questionRepository->select(['id'])
            ->findOrFailById($question);
        ApiResponse::validate($request->all(), [
            'type' => ['required', new EnumValue(QuestionAnswerType::class)],
        ]);
        $questionAnswerType = $this->questionAnswerTypeServiceRepository->create([
            'user_id' => $request->user()->id,
            'question_id' => $question->id,
            'type' => $request->type,
            'priority' => $questionRepository->getMaximumPriorityQuestion($question) + 1,
        ]);
        $questionAnswerType = $this->questionAnswerTypeServiceRepository->select(['id', 'question_id', 'type', 'priority'])
            ->findOrFailById($questionAnswerType->id);
        return ApiResponse::message(trans("The :attribute was successfully registered", ['attribute' => trans('QuestionAnswerType')]), Response::HTTP_CREATED)
            ->addData('questionAnswerType', new QuestionAnswerTypeResource($questionAnswerType))
            ->send();
    }

    /**
     * @param Request $request
     * @param $questionAnswerType
     * @return JsonResponse
     */
    public function update(Request $request, $questionAnswerType): JsonResponse
    {
        $questionAnswerType = $this->questionAnswerTypeServiceRepository->select(['id'])
            ->findOrFailById($questionAnswerType);
        ApiResponse::validate($request->all(), [
            'type' => ['required', new EnumValue(QuestionAnswerType::class)],
        ]);
        $this->questionAnswerTypeServiceRepository->update($questionAnswerType, [
            'type' => $request->type,
        ]);
        $questionAnswerType = $this->questionAnswerTypeServiceRepository->select(['id', 'question_id', 'type', 'priority'])
            ->findOrFailById($questionAnswerType->id);
        return ApiResponse::message(trans("The :attribute was successfully updated", ['attribute' => trans('QuestionAnswerType')]))
            ->addData('questionAnswerType', new QuestionAnswerTypeResource($questionAnswerType))
            ->send();
    }

    public function changePriority(Request $request, $question)
    {
        $question = resolve(QuestionRepositoryInterface::class)->with(['answerTypes:id,question_id'])
            ->select(['id'])
            ->findOrFailById($question);
        $ids = $question->answerTypes->pluck('id');
        ApiResponse::validate($request->all(), [
            'answer_type_ids' => ['bail', 'required', 'array', 'size:' . $ids->count(), Rule::in($ids->toArray())],
        ]);
        try {
            return DB::transaction(function () use ($request, $question) {
                $this->questionAnswerTypeServiceRepository->resetAnswerTypesPriority($question, $request->answer_type_ids);
                return ApiResponse::message(trans("Mission accomplished"))->send();
            });
        } catch (Throwable $e) {
            return ApiResponse::error(trans("Internal server error"))->send();
        }
    }

    /**
     * @param Request $request
     * @param $questionAnswerType
     * @return JsonResponse
     */
    public function destroy(Request $request, $questionAnswerType): JsonResponse
    {
        $questionAnswerType = $this->questionAnswerTypeServiceRepository->select(['id'])
            ->findOrFailById($questionAnswerType);
        $this->questionAnswerTypeServiceRepository->destroy($questionAnswerType);
        return ApiResponse::message(trans("The :attribute was successfully deleted", ['attribute' => trans('QuestionAnswerType')]))->send();
    }

    #endregion
}
