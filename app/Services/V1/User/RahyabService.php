<?php

namespace App\Services\V1\User;

use AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder;
use App\Enums\Role as RoleEnum;
use App\Http\Resources\V1\Exercise\ExerciseResource;
use App\Http\Resources\V1\PaginationResource;
use App\Http\Resources\V1\Question\QuestionResource;
use App\Http\Resources\V1\Rahjoo\RahjooResource;
use App\Models\Exercise;
use App\Models\IntelligencePackage;
use App\Models\Question;
use App\Models\Rahjoo;
use App\Repositories\V1\User\Interfaces\UserRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RahyabService extends BaseService
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
     * @param $rahyab
     * @return JsonResponse
     */
    public function packages(Request $request, $rahyab): JsonResponse
    {
        $rahyab = $this->userRepository
            ->hasRole(RoleEnum::RAHYAB)
            ->findOrFailById($rahyab);
        $rahjoos = Rahjoo::query()
            ->select(['id', 'user_id', 'rahyab_id', 'package_id', 'code'])
            ->lastExercise()
            ->where('rahyab_id', $rahyab->id)
            ->with(['rahyab:id,first_name,last_name', 'user:id,first_name,last_name', 'package:id,title'])
            ->paginate();
        $resource = PaginationResource::make($rahjoos)->additional(['itemsResource' => RahjooResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('rahjoos', $resource)
            ->send();
    }

    /**
     * @param Request $request
     * @param $rahyab
     * @param $rahjoo
     * @return JsonResponse
     */
    public function exercises(Request $request, $rahyab, $rahjoo): JsonResponse
    {
        $rahyab = $this->userRepository
            ->hasRole(RoleEnum::RAHYAB)
            ->findOrFailById($rahyab);
        /** @var Rahjoo $rahjoo */
        $rahjoo = Rahjoo::query()
            ->where('rahyab_id', $rahyab->id)
            ->findOrFail($rahjoo);
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
            })
            ->paginate();
        $resource = PaginationResource::make($exercises)->additional(['itemsResource' => ExerciseResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('exercises', $resource)
            ->send();
    }

    /**
     * @param Request $request
     * @param $rahyab
     * @param $rahjoo
     * @param $exercise
     * @return JsonResponse
     */
    public function questions(Request $request, $rahyab, $rahjoo, $exercise): JsonResponse
    {
        $rahyab = $this->userRepository
            ->hasRole(RoleEnum::RAHYAB)
            ->findOrFailById($rahyab);
        /** @var Rahjoo $rahjoo */
        $rahjoo = Rahjoo::query()
            ->where('rahyab_id', $rahyab->id)
            ->findOrFail($rahjoo);
        /** @var Exercise $exercise */
        $exercise = $rahjoo->packageExercises()->findOrFail($exercise);
        $questions = $exercise->questions()
            ->withWhereHas('answerTypes', function ($q) use ($request, $rahjoo) {
                $q->when($request->filled('answered'), function ($q) use ($request, $rahjoo) {
                    $q->whereHas('answer', function ($q) use ($request, $rahjoo) {
                        $q->where('rahjoo_id', $rahjoo->id);
                    });
                })->when($request->filled('notAnswered'), function ($q) use ($request, $rahjoo) {
                    $q->whereDoesntHave('answer', function ($q) use ($request, $rahjoo) {
                        $q->where('rahjoo_id', $rahjoo->id);
                    });
                })->with(['answer' => function ($q) use ($rahjoo) {
                    $q->where('rahjoo_id', $rahjoo->id);
                }]);
            })->paginate();
        $resource = PaginationResource::make($questions)->additional(['itemsResource' => QuestionResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('questions', $resource)
            ->send();
    }

    public function question(Request $request, $rahyab, $rahjoo, $exercise, $question)
    {
        $rahyab = $this->userRepository
            ->hasRole(RoleEnum::RAHYAB)
            ->findOrFailById($rahyab);
        /** @var Rahjoo $rahjoo */
        $rahjoo = Rahjoo::query()
            ->where('rahyab_id', $rahyab->id)
            ->findOrFail($rahjoo);
        /** @var Exercise $exercise */
        $exercise = $rahjoo->packageExercises()->findOrFail($exercise);
        $question = $exercise->questions()
            ->with(['answerTypes' => function ($q) use ($request, $rahjoo) {
                $q->with(['answer' => function ($q) use ($rahjoo) {
                    $q->with(['file'])->where('rahjoo_id', $rahjoo->id);
                }]);
            }])->findOrFail($question);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('question', new QuestionResource($question))
            ->send();
    }

    #endregion
}
