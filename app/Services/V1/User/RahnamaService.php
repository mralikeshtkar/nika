<?php

namespace App\Services\V1\User;

use App\Enums\Role as RoleEnum;
use App\Http\Resources\V1\Exercise\ExerciseResource;
use App\Http\Resources\V1\PaginationResource;
use App\Http\Resources\V1\Question\QuestionResource;
use App\Http\Resources\V1\Rahjoo\RahjooResource;
use App\Models\Exercise;
use App\Models\Package;
use App\Models\Question;
use App\Models\Rahjoo;
use App\Repositories\V1\User\Interfaces\UserRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RahnamaService extends BaseService
{
    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;

    #region Constructor

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #endregion

    #region Public methods

    /**
     * @param Request $request
     * @param $rahnama
     * @return JsonResponse
     */
    public function packages(Request $request, $rahnama): JsonResponse
    {
        $rahnama = $this->userRepository
            ->hasRole(RoleEnum::RAHNAMA)
            ->with(['pivotRahjooIntelligences'])
            ->findOrFailById($rahnama);
        $rahjoos = Rahjoo::query()
            ->select(['id', 'user_id', 'rahyab_id', 'package_id', 'code'])
            ->lastExercise(true)
            ->whereHas('pivotIntelligenceRahnama', function ($q) use ($rahnama) {
                $q->where('rahnama_id', $rahnama->id);
            })->with(['user:id,first_name,last_name', 'package:id,title'])
            ->paginate();
        $resource = PaginationResource::make($rahjoos)->additional(['itemsResource' => RahjooResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('rahjoos', $resource)
            ->send();
    }

    public function exercises(Request $request, $rahnama, $rahjoo)
    {
        $rahnama = $this->userRepository
            ->hasRole(RoleEnum::RAHNAMA)
            ->findOrFailById($rahnama);
        $rahjoo = Rahjoo::query()
            ->whereHas('pivotIntelligenceRahnama', function ($q) use ($rahnama) {
                $q->where('rahnama_id', $rahnama->id);
            })->findOrFail($rahjoo);
        $exercises = $rahjoo->packageExercises()
            ->whereHas('questions', function ($q) use ($request, $rahjoo) {
                $q->whereHas('answerTypes', function ($q) use ($request, $rahjoo) {
                    $q->when($request->filled('answered'), function ($q) use ($request, $rahjoo) {
                        $q->whereHas('answer', function ($q) use ($request, $rahjoo) {
                            $q->where('rahjoo_id', $rahjoo->id);
                        });
                    })->when($request->filled('notAnswered'), function ($q) use ($request, $rahjoo) {
                        $q->whereDoesntHave('answer', function ($q) use ($request, $rahjoo) {
                            $q->where('rahjoo_id', $rahjoo->id);
                        });
                    });
                });
            })->whereHas('intelligencePackage', function ($q) use ($request, $rahjoo, $rahnama) {
                $q->whereHas('pivotIntelligenceRahjooRahyab', function ($q) use ($request, $rahjoo, $rahnama) {
                    $q->where('rahnama_id', $rahnama->id)
                        ->where('rahjoo_id', $rahjoo->id);
                });
            })->paginate();
        $resource = PaginationResource::make($exercises)->additional(['itemsResource' => ExerciseResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('exercises', $resource)
            ->send();
    }

    /**
     * @param Request $request
     * @param $rahnama
     * @param $rahjoo
     * @param $exercise
     * @return JsonResponse
     */
    public function questions(Request $request, $rahnama, $rahjoo, $exercise): JsonResponse
    {
        $rahnama = $this->userRepository
            ->hasRole(RoleEnum::RAHNAMA)
            ->findOrFailById($rahnama);
        /** @var Rahjoo $rahjoo */
        $rahjoo = Rahjoo::query()
            ->whereHas('pivotIntelligenceRahnama', function ($q) use ($rahnama) {
                $q->where('rahnama_id', $rahnama->id);
            })->findOrFail($rahjoo);
        $exercise = $rahjoo->packageExercises()
            ->whereHas('questions', function ($q) use ($request, $rahjoo) {
                $q->has('answerTypes');
            })->whereHas('intelligencePackage', function ($q) use ($request, $rahjoo, $rahnama) {
                $q->whereHas('pivotIntelligenceRahjooRahyab', function ($q) use ($request, $rahjoo, $rahnama) {
                    $q->where('rahnama_id', $rahnama->id)
                        ->where('rahjoo_id', $rahjoo->id);
                });
            })->findOrFail($exercise);
        /** @var Exercise $exercise */
        $questions = $exercise->questions()
            ->withAggregate('latestAnswer','created_at')
            ->withAggregate('questionDurationStart','start')
            ->withWhereHas('answerTypes', function ($q) use ($request, $rahjoo) {
                $q->with(['answer' => function ($q) use ($rahjoo) {
                    $q->where('rahjoo_id', $rahjoo->id);
                }])->when($request->filled('answered'), function ($q) use ($request, $rahjoo) {
                    $q->whereHas('answer', function ($q) use ($request, $rahjoo) {
                        $q->where('rahjoo_id', $rahjoo->id);
                    });
                })->when($request->filled('notAnswered'), function ($q) use ($request, $rahjoo) {
                    $q->whereDoesntHave('answer', function ($q) use ($request, $rahjoo) {
                        $q->where('rahjoo_id', $rahjoo->id);
                    });
                });
            })->paginate();
        $resource = PaginationResource::make($questions)->additional(['itemsResource' => QuestionResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('exercise', new ExerciseResource($exercise))
            ->addData('questions', $resource)
            ->send();
    }

    /**
     * @param Request $request
     * @param $rahnama
     * @param $rahjoo
     * @param $exercise
     * @param $question
     * @return JsonResponse
     */
    public function question(Request $request, $rahnama, $rahjoo, $exercise, $question): JsonResponse
    {
        $rahnama = $this->userRepository
            ->hasRole(RoleEnum::RAHNAMA)
            ->findOrFailById($rahnama);
        /** @var Rahjoo $rahjoo */
        $rahjoo = Rahjoo::query()
            ->whereHas('pivotIntelligenceRahnama', function ($q) use ($rahnama) {
                $q->where('rahnama_id', $rahnama->id);
            })->findOrFail($rahjoo);
        $exercise = $rahjoo->packageExercises()
            ->whereHas('questions', function ($q) use ($request, $rahjoo) {
                $q->has('answerTypes');
            })->whereHas('intelligencePackage', function ($q) use ($request, $rahjoo, $rahnama) {
                $q->whereHas('pivotIntelligenceRahjooRahyab', function ($q) use ($request, $rahjoo, $rahnama) {
                    $q->where('rahnama_id', $rahnama->id)
                        ->where('rahjoo_id', $rahjoo->id);
                });
            })->findOrFail($exercise);
        $question = $exercise->questions()
            ->withAggregate('latestAnswer','created_at')
            ->withAggregate('questionDurationStart','start')
            ->with(['files' => function ($q) {
                $q->select(['id', 'question_id', 'media_id', 'text'])
                    ->with(['media']);
            }])
            ->withWhereHas('answerTypes', function ($q) use ($request, $rahjoo) {
                $q->with(['answer' => function ($q) use ($rahjoo) {
                    $q->with(['file'])->where('rahjoo_id', $rahjoo->id);
                }]);
            })->findOrFail($question);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('question', new QuestionResource($question))
            ->send();
    }

    #endregion
}
