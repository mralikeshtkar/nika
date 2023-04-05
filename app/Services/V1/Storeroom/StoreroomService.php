<?php

namespace App\Services\V1\Storeroom;

use App\Http\Resources\V1\Package\PackageResource;
use App\Http\Resources\V1\PaginationResource;
use App\Repositories\V1\Package\Interfaces\PackageRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoreroomService extends BaseService
{
    /**
     * @var PackageRepositoryInterface
     */
    private PackageRepositoryInterface $packageRepository;

    /**
     * @param PackageRepositoryInterface $packageRepository
     */
    public function __construct(PackageRepositoryInterface $packageRepository)
    {
        $this->packageRepository = $packageRepository;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $storerooms = $this->packageRepository->select(['id', 'title', 'description', 'quantity', 'created_at'])
            ->filterPagination($request)
            ->paginate($request->get('perPage', 15));
        $resource = PaginationResource::make($storerooms)->additional(['itemsResource' => PackageResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('storerooms', $resource)
            ->send();
    }

    /**
     * @param Request $request
     * @param $package
     * @return JsonResponse
     */
    public function show(Request $request, $package): JsonResponse
    {
        $storeroom = $this->packageRepository->select(['id', 'title', 'description', 'quantity', 'created_at'])
            ->findOrFailById($package);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('storeroom', new PackageResource($storeroom))
            ->send();
    }

    /**
     * @param Request $request
     * @param $package
     * @return JsonResponse
     */
    public function updateQuantity(Request $request, $package): JsonResponse
    {
        $storeroom = $this->packageRepository->select(['id', 'quantity'])
            ->findOrFailById($package);
        ApiResponse::validate($request->all(), [
            'quantity' => ['required', 'numeric', 'min:1', 'max:18446744073709551610']
        ]);
        $this->packageRepository->update($storeroom, [
            'quantity' => $request->quantity,
        ]);
        return ApiResponse::message(trans("Mission accomplished"))->send();
    }
}
