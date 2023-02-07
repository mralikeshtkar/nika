<?php

namespace App\Services\V1\Question;

use App\Enums\Question\QuestionAnswerType;
use App\Http\Resources\V1\Question\QuestionResource;
use App\Models\Question;
use App\Repositories\V1\Exercise\Interfaces\ExerciseRepositoryInterfaces;
use App\Repositories\V1\Package\Interfaces\PackageRepositoryInterface;
use App\Repositories\V1\Question\Interfaces\QuestionAnswerRepositoryInterface;
use App\Repositories\V1\Question\Interfaces\QuestionRepositoryInterface;
use App\Repositories\V1\Rahjoo\Interfaces\RahjooRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class QuestionAnswerService extends BaseService
{
    /**
     * @var QuestionAnswerRepositoryInterface
     */
    private QuestionAnswerRepositoryInterface $questionAnswerRepository;

    #region Constructor

    /**
     * @param QuestionAnswerRepositoryInterface $questionAnswerRepository
     */
    public function __construct(QuestionAnswerRepositoryInterface $questionAnswerRepository)
    {
        $this->questionAnswerRepository = $questionAnswerRepository;
    }

    #endregion

    #region Methods

    /**
     * @param Request $request
     * @param $rahjoo
     * @param $exercise
     * @return JsonResponse|mixed
     */
    public function store(Request $request, $rahjoo, $exercise): mixed
    {
        $rahjoo = resolve(RahjooRepositoryInterface::class)->select(['id', 'package_id'])
            ->with(['package:id'])
            ->findorFailById($rahjoo);
        $exercise = resolve(PackageRepositoryInterface::class)->findPackageExerciseById($request, $rahjoo->package, $exercise);
        ApiResponse::validate($request->all(), [
            'question_id' => ['required', 'exists:' . Question::class . ',id'],
        ]);
        $question = resolve(ExerciseRepositoryInterfaces::class)->findExerciseQuestionById($request,
            $exercise,
            $request->question_id,
            ['id', 'exercise_id', 'title', 'created_at', 'updated_at'],
            ['files', 'answerTypes:id,question_id,type', 'answers:rahjoo_id,question_id'],
        );
        //abort_if($question->answers->contains('rahjoo_id', $rahjoo->id), ApiResponse::error(trans("You have already answered this question"), Response::HTTP_BAD_REQUEST)->send());
        $rules = $question->answerTypes->pluck('type', 'id')->mapWithKeys(function ($item, $key) {
            return [
                'answers.' . $key => QuestionAnswerType::fromValue($item)->getRules()
            ];
        })->put('answers', ['required', 'array']);
        ApiResponse::validate($request->all(), $rules->toArray());
        try {
            return DB::transaction(function () use ($request, $rahjoo, $question) {
                foreach ($request->answers as $key => $answer) {
                    $isFile = $request->hasFile('answers.' . $key);
                    $questionAnswer = $this->questionAnswerRepository->create([
                        'rahjoo_id' => $rahjoo->id,
                        'question_id' => $question->id,
                        'text' => $isFile ? null : $request->input('answers.' . $key),
                    ]);
                    if ($isFile) $this->questionAnswerRepository->uploadFile($questionAnswer, $request->file('answers.' . $key));
                }
                return ApiResponse::message(trans("The :attribute was successfully registered", ['attribute' => trans('QuestionAnswer')]), Response::HTTP_CREATED)->send();
            });
        } catch (Throwable $e) {
            return ApiResponse::error(trans("Internal server error"))->send();
        }
    }

    /**
     * @param Request $request
     * @param $rahjoo
     * @param $exercise
     * @return mixed
     */
    public function storeSingle(Request $request, $rahjoo, $exercise): mixed
    {
        $rahjoo = resolve(RahjooRepositoryInterface::class)->select(['id', 'package_id'])
            ->with(['package:id'])
            ->findorFailById($rahjoo);
        $exercise = resolve(PackageRepositoryInterface::class)->findPackageExerciseById($request, $rahjoo->package, $exercise);
        ApiResponse::validate($request->all(), [
            'question_id' => ['required', 'exists:' . Question::class . ',id'],
            'answer_type_id' => ['required', Rule::exists(\App\Models\QuestionAnswerType::class, 'id')->where('question_id', $request->question_id)],
        ]);
        $question = resolve(ExerciseRepositoryInterfaces::class)->findExerciseQuestionById($request,
            $exercise,
            $request->question_id,
            ['id', 'exercise_id', 'title', 'created_at', 'updated_at'],
            ['files', 'answers:rahjoo_id,question_id'],
        );
        $answerType = resolve(QuestionRepositoryInterface::class)->query($question->answerTypes())
            ->select(['id', 'question_id', 'type'])
            ->findOrFailById($request->answer_type_id);
        //abort_if($question->answers->contains('rahjoo_id', $rahjoo->id), ApiResponse::error(trans("You have already answered this question"), Response::HTTP_BAD_REQUEST)->send());
        ApiResponse::validate($request->all(), [
            'file' => QuestionAnswerType::fromValue($answerType->type)->getRules(),
        ]);
        try {
            return DB::transaction(function () use ($request, $rahjoo, $question) {
                $isFile = $request->hasFile('file');
                $questionAnswer = $this->questionAnswerRepository->create([
                    'rahjoo_id' => $rahjoo->id,
                    'question_id' => $question->id,
                    'text' => $isFile ? null : $request->input('file'),
                ]);
                if ($isFile) $this->questionAnswerRepository->uploadFile($questionAnswer, $request->file('file'));
                return ApiResponse::message(trans("The :attribute was successfully registered", ['attribute' => trans('QuestionAnswer')]), Response::HTTP_CREATED)->send();
            });
        } catch (Throwable $e) {
            return ApiResponse::error(trans("Internal server error"))->send();
        }
    }

    /**
     * @param Request $request
     * @param $rahjoo
     * @param $exercise
     * @param $question
     * @return JsonResponse
     */
    public function showQuestionWithAnswer(Request $request, $rahjoo, $exercise, $question): JsonResponse
    {
        $rahjoo = resolve(RahjooRepositoryInterface::class)->select(['id', 'package_id'])
            ->with(['package:id'])
            ->findorFailById($rahjoo);
        $exercise = resolve(PackageRepositoryInterface::class)->findPackageExerciseById($request, $rahjoo->package, $exercise);
        $question = resolve(ExerciseRepositoryInterfaces::class)->findExerciseQuestionById($request,
            $exercise,
            $question,
            ['id', 'exercise_id', 'title'],
            ['files', 'files.media', 'answerTypes:id,question_id,type', 'answers.file', 'answers' => function ($q) use ($rahjoo) {
                $q->select(['id', 'rahjoo_id', 'question_id', 'text', 'created_at'])
                    ->where('rahjoo_id', $rahjoo->id);
            }],
        );
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('question', new QuestionResource($question))
            ->send();
    }

    #endregion

}
