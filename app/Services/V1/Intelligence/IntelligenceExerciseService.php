<?php

namespace App\Services\V1\Intelligence;

use App\Http\Resources\V1\Exercise\ExerciseResource;
use App\Http\Resources\V1\Intelligence\IntelligenceResource;
use App\Http\Resources\V1\Package\PackageResource;
use App\Http\Resources\V1\PaginationResource;
use App\Repositories\V1\Exercise\Interfaces\ExerciseRepositoryInterfaces;
use App\Repositories\V1\Intelligence\Interfaces\IntelligenceRepositoryInterface;
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
     * @param $package
     * @param $intelligence
     * @return JsonResponse
     */
    public function index(Request $request, $package, $intelligence): JsonResponse
    {
        $packageRepository = resolve(PackageRepositoryInterface::class);
        $package = $packageRepository->select(['id', 'title'])->findOrFailById($package);
        $intelligence = $packageRepository->findOrFailIntelligenceByIntelligences($package, $intelligence, ['intelligences.id', 'title']);
        $exercises = resolve(ExerciseRepositoryInterfaces::class)
            ->select(['id', 'package_id', 'intelligence_id', 'title', 'is_locked', 'created_at'])
            ->getIntelligenceExercises($intelligence->id, $package->id)
            ->searchTitle($request)
            ->paginate($request->get('perPage', 10));
        $resource = PaginationResource::make($exercises)->additional(['itemsResource' => ExerciseResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('package', new PackageResource($package))
            ->addData('intelligence', new IntelligenceResource($intelligence))
            ->addData('exercises', $resource)
            ->send();
    }

    #endregion

}
