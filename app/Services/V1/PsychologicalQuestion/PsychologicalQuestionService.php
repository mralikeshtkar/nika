<?php

namespace App\Services\V1\PsychologicalQuestion;

use App\Http\Resources\V1\PsychologicalQuestion\PsychologicalQuestionResource;
use App\Models\Job;
use App\Models\PsychologicalQuestion;
use App\Models\RahjooCourse;
use App\Models\Skill;
use App\Repositories\V1\PsychologicalQuestion\Interfaces\PsychologicalQuestionRepositoryInterface;
use App\Repositories\V1\Rahjoo\Interfaces\RahjooRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class PsychologicalQuestionService extends BaseService
{
    private PsychologicalQuestionRepositoryInterface $psychologicalQuestionRepository;

    #region Constructor

    /**
     * @param PsychologicalQuestionRepositoryInterface $psychologicalQuestionRepository
     */
    public function __construct(PsychologicalQuestionRepositoryInterface $psychologicalQuestionRepository)
    {
        $this->psychologicalQuestionRepository = $psychologicalQuestionRepository;
    }

    #endregion

    #region Public methods

    /**
     * Update or store psychological question.
     *
     * @param Request $request
     * @param $rahjoo
     * @return JsonResponse
     */
    public function store(Request $request, $rahjoo): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('create', PsychologicalQuestion::class));
        $rahjoo = resolve(RahjooRepositoryInterface::class)->findorFailById($rahjoo);
        ApiResponse::validate($request->all(), [
            'favourite_job_id' => ['nullable', 'exists:' . Job::class . ',id'],
            'parent_favourite_job_id' => ['nullable', 'exists:' . Job::class . ',id'],
            'negative_positive_points' => ['nullable', 'string'],
            'favourites' => ['nullable', 'string'],
            'skills' => ['nullable', 'array'],
            'skills.*' => ['exists:' . Skill::class . ',id'],
        ], [], [
            'skills' => trans('Skills'),
            'skills.*' => trans('Skills'),
        ]);
        try {
            return DB::transaction(function () use ($request, $rahjoo) {
                $psychologicalQuestion = $this->psychologicalQuestionRepository->updateOrCreate([
                    'rahjoo_id' => $rahjoo->id,
                ], [
                    'user_id' => $request->user()->id,
                    'rahjoo_id' => $rahjoo->id,
                    'favourite_job_id' => $request->favourite_job_id,
                    'parent_favourite_job_id' => $request->parent_favourite_job_id,
                    'negative_positive_points' => $request->negative_positive_points,
                    'favourites' => $request->favourites,
                ]);
                if ($request->filled('skills')) $psychologicalQuestion->skills()->sync($request->skills);
                $psychologicalQuestion = $this->psychologicalQuestionRepository->with(['skills:id,title'])->findOrFailById($psychologicalQuestion->id);
                return ApiResponse::message(trans("The :attribute was successfully registered", ['attribute' => trans('PsychologicalQuestion')]), Response::HTTP_CREATED)
                    ->addData('psychologicalQuestion', new PsychologicalQuestionResource($psychologicalQuestion))
                    ->send();
            });
        } catch (Throwable $e) {
            return ApiResponse::error(trans("Internal server error"))->send();
        }
    }

    #endregion
}
