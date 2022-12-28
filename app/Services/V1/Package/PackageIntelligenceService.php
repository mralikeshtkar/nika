<?php

namespace App\Services\V1\Package;

use App\Http\Resources\V1\Intelligence\IntelligenceResource;
use App\Http\Resources\V1\PaginationResource;
use App\Models\Intelligence;
use App\Models\Package;
use App\Repositories\V1\Package\Interfaces\PackageRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PackageIntelligenceService extends BaseService
{
    private PackageRepositoryInterface $packageRepository;

    #region Constructor

    public function __construct(PackageRepositoryInterface $packageRepository)
    {
        $this->packageRepository = $packageRepository;
    }

    #endregion

    #region Public methods

    /**
     * @param Request $request
     * @param $package
     * @return JsonResponse
     */
    public function index(Request $request, $package): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('intelligence', Package::class));
        $package = $this->packageRepository->select(['id'])->findOrFailById($package);
        $intelligences = $this->packageRepository->getPackageIntelligences($package, $request);
        $resource = PaginationResource::make($intelligences)->additional(['itemsResource' => IntelligenceResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('intelligences', $resource)
            ->send();
    }

    /**
     * @param Request $request
     * @param $package
     * @return JsonResponse
     */
    public function store(Request $request, $package): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('intelligence', Package::class));
        $package = $this->packageRepository->with(['intelligences:id'])
            ->select(['id'])
            ->findOrFailById($package);
        ApiResponse::validate($request->all(), [
            'intelligence_id' => ['required', 'exists:' . Intelligence::class . ',id'],
        ]);
        if ($package->intelligences->contains($request->intelligence_id))
            return ApiResponse::error(trans("Selective intelligence is registered for this package"), Response::HTTP_BAD_REQUEST)->send();
        $this->packageRepository->syncIntelligences($package, [$request->intelligence_id]);
        return ApiResponse::message(trans("The information was register successfully"))->send();
    }

    /**
     * @param Request $request
     * @param $package
     * @param $intelligence
     * @return JsonResponse
     */
    public function completed(Request $request, $package, $intelligence): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('intelligence', Package::class));
        $package = $this->packageRepository->select(['id'])->findOrFailById($package);
        $intelligence = $this->packageRepository->findIntelligenceOrFailById($package, $intelligence, ['id']);
        $this->packageRepository->intelligenceCompleted($package, $intelligence->id);
        $intelligence = $this->packageRepository->findIntelligenceOrFailById($package, $intelligence->id, ['id']);
        return ApiResponse::message(trans("Mission accomplished"))
            ->addData('intelligence', new IntelligenceResource($intelligence))
            ->send();
    }

    /**
     * @param Request $request
     * @param $package
     * @param $intelligence
     * @return JsonResponse
     */
    public function uncompleted(Request $request, $package, $intelligence): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('intelligence', Package::class));
        $package = $this->packageRepository->select(['id'])->findOrFailById($package);
        $intelligence = $this->packageRepository->findIntelligenceOrFailById($package, $intelligence, ['id']);
        $this->packageRepository->intelligenceUncompleted($package, $intelligence->id);
        $intelligence = $this->packageRepository->findIntelligenceOrFailById($package, $intelligence->id, ['id']);
        return ApiResponse::message(trans("Mission accomplished"))
            ->addData('intelligence', new IntelligenceResource($intelligence))
            ->send();
    }

    #endregion
}
