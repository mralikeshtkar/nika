<?php

namespace App\Services\V1\Question;

use App\Enums\Question\QuestionAnswerType;
use App\Http\Resources\V1\Question\QuestionResource;
use App\Models\Question;
use App\Repositories\V1\Exercise\Interfaces\ExerciseRepositoryInterfaces;
use App\Repositories\V1\Package\Interfaces\PackageRepositoryInterface;
use App\Repositories\V1\Question\Interfaces\QuestionAnswerRepositoryInterface;
use App\Repositories\V1\Rahjoo\Interfaces\RahjooRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        abort_if($question->answers->contains('rahjoo_id', $rahjoo->id), ApiResponse::error(trans("You have already answered this question"), Response::HTTP_BAD_REQUEST)->send());
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
            logger($e);
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
    public function showQuestionWithAnswers(Request $request, $rahjoo, $exercise, $question): JsonResponse
    {
        $rahjoo = resolve(RahjooRepositoryInterface::class)->select(['id', 'package_id'])
            ->with(['package:id'])
            ->findorFailById($rahjoo);
        $exercise = resolve(PackageRepositoryInterface::class)->findPackageExerciseById($request, $rahjoo->package, $exercise);
        $question = resolve(ExerciseRepositoryInterfaces::class)->findExerciseQuestionById($request,
            $exercise,
            $question,
            ['id', 'exercise_id', 'title'],
            ['files', 'answerTypes:id,question_id,type', 'answers:id,rahjoo_id,question_id,text,created_at', 'answers.file'],
        );
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('question', new QuestionResource($question))
            ->send();
    }

    #endregion

}