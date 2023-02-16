<?php

namespace App\Services\V1\User;

use App\Enums\Role as RoleEnum;
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

    #endregion
}
