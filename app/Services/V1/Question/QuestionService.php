<?php

namespace App\Services\V1\Question;

use App\Http\Resources\V1\Comment\CommentResource;
use App\Http\Resources\V1\IntelligencePoint\IntelligencePointResource;
use App\Http\Resources\V1\PaginationResource;
use App\Http\Resources\V1\Question\QuestionAnswerTypeResource;
use App\Http\Resources\V1\Question\QuestionResource;
use App\Http\Resources\V1\Rahjoo\RahjooResource;
use App\Models\MediaQuestion;
use App\Models\Question;
use App\Repositories\V1\Exercise\Interfaces\ExerciseRepositoryInterfaces;
use App\Repositories\V1\Media\Interfaces\MediaRepositoryInterface;
use App\Repositories\V1\Package\Interfaces\IntelligencePackageRepositoryInterface;
use App\Repositories\V1\Question\Interfaces\QuestionRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class QuestionService extends BaseService
{
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
    public function show(Request $request, $question): JsonResponse
    {
        $question = $this->questionRepository->with([
            'files',
            'intelligencePackage',
            'pivotPoints' => function (HasMany $hasMany) {
                $hasMany->withGroupIntelligencePointId();
            },
            'exercise' => function (BelongsTo $belongsTo) {
                $belongsTo->select(['id', 'title'])
                    ->with('pivotPoints');
            },
        ])->select(['id', 'exercise_id', 'title', 'created_at', 'updated_at'])
            ->findOrFailById($question);
        $points = resolve(IntelligencePackageRepositoryInterface::class)->getPoints($request, $question->intelligencePackage);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('question', new QuestionResource($question))
            ->addData('intelligencePoints', IntelligencePointResource::collection($points, ['withRemind' => $question->pivotPoints]))
            ->send();
    }

    /**
     * @param Request $request
     * @param $exercise
     * @return JsonResponse
     */
    public function store(Request $request, $exercise): JsonResponse
    {
        $exerciseRepository = resolve(ExerciseRepositoryInterfaces::class);
        $exercise = $exerciseRepository->select(['id'])
            ->findOrFailById($exercise);
        ApiResponse::validate($request->all(), [
            'title' => ['required', 'string'],
        ]);
        $question = $this->questionRepository->create([
            'user_id' => $request->user()->id,
            'exercise_id' => $exercise->id,
            'title' => $request->title,
            'priority' => $exerciseRepository->getMaximumQuestionsPriority($exercise) + 1
        ]);
        return ApiResponse::message(trans("The :attribute was successfully registered", ['attribute' => trans('Question')]), Response::HTTP_CREATED)
            ->addData('question', new QuestionResource($question))
            ->send();
    }

    /**
     * @param Request $request
     * @param $exercise
     * @return JsonResponse|mixed
     */
    public function changePriorityQuestion(Request $request, $exercise): mixed
    {
        $exerciseRepository = resolve(ExerciseRepositoryInterfaces::class);
        $exercise = $exerciseRepository->select(['id'])
            ->with(['questions:id,exercise_id'])
            ->findOrFailById($exercise);
        $questions = $exercise->questions->pluck('id');
        ApiResponse::validate($request->all(), [
            'ids' => ['required', 'array', 'size:' . $questions->count(), Rule::in($questions->toArray())],
        ]);
        try {
            return DB::transaction(function () use ($exerciseRepository, $exercise, $request) {
                $exerciseRepository->resetQuestionPriorities($exercise, $request->ids);
                return ApiResponse::message(trans("Mission accomplished"))->send();
            });
        } catch (Throwable $e) {
            return ApiResponse::error(trans("Internal server error"))->send();
        }
    }

    /**
     * @param Request $request
     * @param $question
     * @return JsonResponse
     */
    public function files(Request $request, $question): JsonResponse
    {
        $question = $this->questionRepository->select(['id'])
            ->with(['files', 'files.media'])
            ->findOrFailById($question);
        return ApiResponse::message(trans('The information was received successfully'))
            ->addData('question', new QuestionResource($question))
            ->send();
    }

    /**
     * @param Request $request
     * @param $question
     * @return JsonResponse|mixed
     */
    public function uploadFile(Request $request, $question): mixed
    {
        $question = $this->questionRepository->select(['id'])
            ->withMaximum('pivotMedia', 'priority')
            ->findOrFailById($question);
        ApiResponse::validate($request->all(), [
            'file' => ['nullable', 'file'], //todo set maximum size file
            'text' => ['required_without:file', 'string'],
        ]);
        try {
            return DB::transaction(function () use ($request, $question) {
                $this->questionRepository->storeFiles($question, [
                    'media_id' => $request->hasFile('file') ? $this->questionRepository->uploadFile($question, $request->file)->id : null,
                    'text' => $request->filled('text') ? $request->text : null,
                    'priority' => intval($question->pivot_media_max_priority) + 1,
                ]);
                $question = $this->questionRepository->with(['files'])
                    ->select(['id', 'title'])
                    ->findOrFailById($question->id);
                return ApiResponse::message(trans("Mission accomplished"))
                    ->addData('question', new QuestionResource($question))
                    ->send();
            });
        } catch (Throwable $e) {
            return ApiResponse::error(trans("Internal server error"))->send();
        }
    }

    /**
     * @param Request $request
     * @param $question
     * @return JsonResponse|mixed
     */
    public function removeFile(Request $request, $question): mixed
    {
        $question = $this->questionRepository->select(['id'])
            ->findOrFailById($question);
        ApiResponse::validate($request->all(), [
            'item_id' => ['required', Rule::exists(MediaQuestion::class, 'id')
                ->where('question_id', $question->id)]
        ]);
        try {
            return DB::transaction(function () use ($request, $question) {
                $mediaQuestion = $this->questionRepository->findOrFailFilesById($question, $request->item_id);
                $this->questionRepository->destroyFile($question, $mediaQuestion->id);
                return ApiResponse::message(trans("Mission accomplished"))->send();
            });
        } catch (Throwable $e) {
            return ApiResponse::error(trans("Internal server error"))->send();
        }
    }

    /**
     * @param Request $request
     * @param $question
     * @return JsonResponse
     */
    public function update(Request $request, $question): JsonResponse
    {
        $question = $this->questionRepository->select(['id', 'title'])->findOrFailById($question);
        ApiResponse::validate($request->all(), [
            'title' => ['required', 'string'],
        ]);
        $this->questionRepository->update($question, [
            'title' => $request->title,
        ]);
        $question = $this->questionRepository->select(['id', 'title'])->findOrFailById($question->id);
        return ApiResponse::message(trans("The :attribute was successfully updated", ['attribute' => trans('Question')]))
            ->addData('question', new QuestionResource($question))
            ->send();
    }

    /**
     * @param Request $request
     * @param $question
     * @return mixed
     */
    public function changeFilePriority(Request $request, $question): mixed
    {
        $question = $this->questionRepository->with(['files:id,question_id'])
            ->select(['id'])
            ->findOrFailById($question);
        $files = $question->files->pluck('id');
        ApiResponse::validate($request->all(), [
            'ids' => ['bail', 'required', 'array', 'size:' . $files->count(), Rule::in($files)],
        ]);
        try {
            return DB::transaction(function () use ($request, $question) {
                $this->questionRepository->resetFilesPriority($question, $request->ids);
                return ApiResponse::message(trans("Mission accomplished"))->send();
            });
        } catch (Throwable $e) {
            return ApiResponse::error(trans("Internal server error"))->send();
        }
    }

    /**
     * @param Request $request
     * @param $question
     * @return JsonResponse
     */
    public function answerTypes(Request $request, $question): JsonResponse
    {
        $question = $this->questionRepository->select(['id'])->findOrFailById($question);
        $answerTypes = $this->questionRepository->getAnswerTypes($question);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('answerTypes', QuestionAnswerTypeResource::collection($answerTypes))
            ->send();
    }

    /**
     * @param Request $request
     * @param $question
     * @return JsonResponse
     */
    public function answers(Request $request, $question): JsonResponse
    {
        $question = $this->questionRepository->select(['id'])->findOrFailById($question);
        $answers = $this->questionRepository->getPaginateAnswers($request, $question);
        $resource = PaginationResource::make($answers)->additional(['itemsResource' => RahjooResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('answers', $resource)
            ->send();
    }

    /**
     * @param Request $request
     * @param $question
     * @return JsonResponse
     */
    public function destroy(Request $request, $question): JsonResponse
    {
        $question = $this->questionRepository->select(['id'])->findOrFailById($question);
        $this->questionRepository->destroy($question);
        return ApiResponse::message(trans("The :attribute was successfully deleted", ['attribute' => trans('Question')]))->send();
    }

    #endregion

}
