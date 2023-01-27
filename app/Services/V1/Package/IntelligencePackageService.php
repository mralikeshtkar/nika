<?php

namespace App\Services\V1\Package;

use App\Http\Resources\V1\Intelligence\IntelligenceResource;
use App\Http\Resources\V1\IntelligenceFeedback\IntelligenceFeedbackResource;
use App\Http\Resources\V1\IntelligencePoint\IntelligencePointResource;
use App\Http\Resources\V1\Package\PackageIntelligenceResource;
use App\Http\Resources\V1\Package\PackageResource;
use App\Http\Resources\V1\PaginationResource;
use App\Models\Intelligence;
use App\Models\Package;
use App\Repositories\V1\Package\Interfaces\IntelligencePackageRepositoryInterface;
use App\Repositories\V1\Package\Interfaces\PackageRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IntelligencePackageService extends BaseService
{
    private PackageRepositoryInterface $packageRepository;


    private IntelligencePackageRepositoryInterface $intelligencePackageRepository;

    #region Constructor

    /**
     * @param IntelligencePackageRepositoryInterface $intelligencePackageRepository
     * @param PackageRepositoryInterface $packageRepository
     */
    public function __construct(IntelligencePackageRepositoryInterface $intelligencePackageRepository, PackageRepositoryInterface $packageRepository)
    {
        $this->packageRepository = $packageRepository;
        $this->intelligencePackageRepository = $intelligencePackageRepository;
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
     * @param $intelligencePackage
     * @return JsonResponse
     */
    public function show(Request $request, $intelligencePackage): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('intelligence', Package::class));
        $intelligencePackage = $this->intelligencePackageRepository
            ->select(['pivot_id', 'package_id', 'intelligence_id', 'is_completed','created_at','updated_at'])
            ->with(['intelligence:id,title,created_at,updated_at'])
            ->findOrFailByPivotId($intelligencePackage);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('packageIntelligence', new PackageIntelligenceResource($intelligencePackage))
            ->send();
    }

    /**
     * @param Request $request
     * @param $intelligencePackage
     * @return JsonResponse
     */
    public function points(Request $request, $intelligencePackage): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('intelligence', Package::class));
        $intelligencePackage = $this->intelligencePackageRepository
            ->select(['pivot_id'])
            ->findOrFailByPivotId($intelligencePackage);
        $points = $this->intelligencePackageRepository->getPoints($request,$intelligencePackage);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('points', IntelligencePointResource::collection($points))
            ->send();
    }

    /**
     * @param Request $request
     * @param $intelligencePackage
     * @return JsonResponse
     */
    public function feedbacks(Request $request, $intelligencePackage): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('intelligence', Package::class));
        $intelligencePackage = $this->intelligencePackageRepository
            ->select(['pivot_id'])
            ->findOrFailByPivotId($intelligencePackage);
        $feedbacks = $this->intelligencePackageRepository->getFeedbacks($intelligencePackage);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('feedbacks', IntelligenceFeedbackResource::collection($feedbacks))
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
        $this->packageRepository->attachIntelligences($package, [$request->intelligence_id]);
        $intelligencePackage = $this->intelligencePackageRepository->select(['pivot_id', 'package_id', 'intelligence_id', 'is_completed'])
            ->findOrFailByPackageAndIntelligenceId($package->id, $request->intelligence_id);
        return ApiResponse::message(trans("The information was register successfully"))
            ->addData('packageIntelligence', new PackageIntelligenceResource($intelligencePackage))
            ->send();
    }

    /**
     * @param Request $request
     * @param $intelligencePackage
     * @return JsonResponse
     */
    public function completed(Request $request, $intelligencePackage): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('intelligence', Package::class));
        return $this->_changeCompleteStatus($intelligencePackage, true);
    }

    /**
     * @param Request $request
     * @param $intelligencePackage
     * @return JsonResponse
     */
    public function uncompleted(Request $request, $intelligencePackage): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('intelligence', Package::class));
        return $this->_changeCompleteStatus($intelligencePackage, false);
    }

    /**
     * @param Request $request
     * @param $intelligencePackage
     * @return JsonResponse
     */
    public function destroy(Request $request, $intelligencePackage): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('intelligence', Package::class));
        $intelligencePackage = $this->intelligencePackageRepository->select(['pivot_id'])
            ->findOrFailByPivotId($intelligencePackage);
        $this->intelligencePackageRepository->destroy($intelligencePackage);
        return ApiResponse::message(trans("Mission accomplished"))->send();
    }

    #endregion

    /**
     * @param $intelligencePackage
     * @param $is_completed
     * @return JsonResponse
     */
    private function _changeCompleteStatus($intelligencePackage, $is_completed): JsonResponse
    {
        $intelligencePackage = $this->intelligencePackageRepository
            ->select(['pivot_id', 'is_completed'])
            ->findOrFailByPivotId($intelligencePackage);
        $this->intelligencePackageRepository->update($intelligencePackage, ['is_completed' => $is_completed]);
        $intelligencePackage = $this->intelligencePackageRepository
            ->select(['pivot_id', 'package_id', 'intelligence_id', 'is_completed'])
            ->findOrFailByPivotId($intelligencePackage->pivot_id);
        return ApiResponse::message(trans("Mission accomplished"))
            ->addData('packageIntelligence', new PackageIntelligenceResource($intelligencePackage))
            ->send();
    }
}
