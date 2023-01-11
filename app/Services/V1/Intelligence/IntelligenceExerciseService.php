<?php

namespace App\Services\V1\Intelligence;

use App\Http\Resources\V1\Exercise\ExerciseResource;
use App\Http\Resources\V1\Intelligence\IntelligenceResource;
use App\Http\Resources\V1\Package\PackageResource;
use App\Http\Resources\V1\PaginationResource;
use App\Repositories\V1\Exercise\Interfaces\ExerciseRepositoryInterfaces;
use App\Repositories\V1\Intelligence\Interfaces\IntelligenceRepositoryInterface;
use App\Repositories\V1\Package\Interfaces\IntelligencePackageRepositoryInterface;
use App\Repositories\V1\Package\Interfaces\PackageRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IntelligenceExerciseService extends BaseService
{
    private IntelligenceRepositoryInterface $intelligenceRepository;

    #region Constructor

    /**
     * @param IntelligenceRepositoryInterface $intelligenceRepository
     */
    public function __construct(IntelligenceRepositoryInterface $intelligenceRepository)
    {
        $this->intelligenceRepository = $intelligenceRepository;
    }

    #endregion

    #region Public methods

    /**
     * @param Request $request
     * @param $intelligencePackage
     * @return JsonResponse
     */
    public function index(Request $request, $intelligencePackage): JsonResponse
    {
        $intelligencePackageRepository = resolve(IntelligencePackageRepositoryInterface::class);
        $intelligencePackage = $intelligencePackageRepository->findOrFailByPivotId($intelligencePackage);
        $exercises = resolve(ExerciseRepositoryInterfaces::class)
            ->select(['id', 'intelligence_package_id', 'title', 'is_locked', 'created_at'])
            ->whereIntelligencePackageId($intelligencePackage->pivot_id)
            ->searchTitle($request)
            ->paginate($request->get('perPage', 10));
        $resource = PaginationResource::make($exercises)->additional(['itemsResource' => ExerciseResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('exercises', $resource)
            ->send();
    }

    #endregion

}
