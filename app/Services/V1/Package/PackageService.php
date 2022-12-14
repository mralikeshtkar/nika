<?php

namespace App\Services\V1\Package;

use App\Enums\Media\MediaExtension;
use App\Enums\Package\PackageStatus;
use App\Http\Resources\V1\Package\PackageResource;
use App\Http\Resources\V1\PaginationResource;
use App\Models\Intelligence;
use App\Models\Package;
use App\Repositories\V1\Media\Interfaces\MediaRepositoryInterface;
use App\Repositories\V1\Package\Interfaces\PackageRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use BenSampo\Enum\Rules\EnumKey;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class PackageService extends BaseService
{
    /**
     * @var PackageRepositoryInterface
     */
    private PackageRepositoryInterface $packageRepository;

    #region Constance

    /**
     * @param PackageRepositoryInterface $packageRepository
     */
    public function __construct(PackageRepositoryInterface $packageRepository)
    {
        $this->packageRepository = $packageRepository;
    }

    #endregion

    #region Public methods

    /**
     * Get all packages as pagination.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('index', Package::class));
        $packages = $this->packageRepository->select(['id', 'title', 'status', 'age', 'price', 'is_completed', 'description', 'created_at'])
            ->filterPagination($request)
            ->paginate($request->get('perPage', 10));
        $resource = PaginationResource::make($packages)->additional(['itemsResource' => PackageResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('packages', $resource)
            ->send();
    }

    /**
     * @param Request $request
     * @param $package
     * @return JsonResponse
     */
    public function show(Request $request, $package): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('show', Package::class));
        $package = $this->packageRepository->with(['video'])->findOrFailById($package);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('package', new PackageResource($package))
            ->send();
    }

    /**
     * Store a package.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('create', Package::class));
        ApiResponse::validate($request->all(), [
            'title' => ['required', 'string'],
            'status' => ['nullable', new EnumKey(PackageStatus::class)],
            'age' => ['required', 'numeric', 'min:1'],
            'price' => ['required', 'numeric'],//todo set minimum price from setting
            'is_completed' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],
            'video' => ['nullable', 'file', 'mimes:' . implode(",", MediaExtension::getExtensions(MediaExtension::Video))],
            'intelligences' => ['nullable', 'array'],
            'intelligences.*' => ['exists:' . Intelligence::class . ',id'],
        ]);
        $request->merge(['status' => $request->filled('status') ? PackageStatus::coerce($request->status) : null,]);
        try {
            return DB::transaction(function () use ($request) {
                $package = $this->packageRepository->create(collect([
                    'user_id' => $request->user()->id,
                    'title' => $request->title,
                    'age' => $request->age,
                    'price' => $request->price,
                    'description' => $request->description,
                ])->when($request->filled('status'), function (Collection $collection) use ($request) {
                    $collection->put('status', $request->status->value);
                })->when($request->filled('is_completed'), function (Collection $collection) use ($request) {
                    $collection->put('is_completed', $request->is_completed);
                })->toArray());
                $this->packageRepository->uploadVideo($package, $request->video);
                $package = $this->packageRepository->with(['video'])->findOrFailById($package->id);
                if ($request->filled('intelligences'))
                    $this->packageRepository->syncIntelligences($package, $request->get('intelligences', []));
                return ApiResponse::message(trans("The :attribute was successfully registered", ['attribute' => trans('Package')]), Response::HTTP_CREATED)
                    ->addData('package', new PackageResource($package))
                    ->send();
            });
        } catch (Throwable $e) {
            return ApiResponse::error(trans("Internal server error"))->send();
        }
    }

    /**
     * @param Request $request
     * @param $package
     * @return JsonResponse
     */
    public function uploadVideo(Request $request, $package): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('create', Package::class));
        $package = $this->packageRepository->findOrFailById($package);
        ApiResponse::validate($request->all(), [
            'video' => ['required', 'file', 'mimes:' . implode(",", MediaExtension::getExtensions(MediaExtension::Video))]
        ]);
        resolve(MediaRepositoryInterface::class)->destroy($package->video);
        $this->packageRepository->uploadVideo($package, $request->video);
        $package = $this->packageRepository->with(['video'])->findOrFailById($package->id);
        return ApiResponse::message(trans("The video package has been uploaded successfully"))
            ->addData('package', new PackageResource($package))
            ->send();
    }

    /**
     * Update a package.
     *
     * @param Request $request
     * @param $package
     * @return JsonResponse
     */
    public function update(Request $request, $package): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('edit', Package::class));
        ApiResponse::validate($request->all(), [
            'status' => ['nullable', new EnumKey(PackageStatus::class)],
            'title' => ['required', 'string'],
            'age' => ['required', 'numeric', 'min:1'],
            'price' => ['required', 'numeric'],//todo set minimum price from setting
            'is_completed' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],
            'video' => ['nullable', 'file', 'mimes:' . implode(",", MediaExtension::getExtensions(MediaExtension::Video))],
            'intelligences' => ['nullable', 'array'],
            'intelligences.*' => ['exists:' . Intelligence::class . ',id'],
        ]);
        $request->merge([
            'status' => $request->filled('status') ? PackageStatus::coerce($request->status) : null,
        ]);
        $package = $this->packageRepository->findOrFailById($package);
        try {
            $this->packageRepository->update($package, collect([
                'user_id' => $request->user()->id,
                'title' => $request->title,
                'age' => $request->age,
                'price' => $request->price,
                'description' => $request->description,
            ])->when($request->filled('status'), function (Collection $collection) use ($request) {
                $collection->put('status', $request->status->value);
            })->when($request->filled('is_completed'), function (Collection $collection) use ($request) {
                $collection->put('is_completed', $request->is_completed);
            })->toArray());
            resolve(MediaRepositoryInterface::class)->destroy($package->video);
            $this->packageRepository->uploadVideo($package, $request->video);
            $package = $this->packageRepository->with(['video'])->findOrFailById($package->id);
            if ($request->filled('intelligences'))
                $this->packageRepository->syncIntelligences($package, $request->get('intelligences', []));
            return ApiResponse::message(trans("The :attribute was successfully updated", ['attribute' => trans('Package')]))
                ->addData('package', new PackageResource($package))
                ->send();
        } catch (Throwable $e) {
            return ApiResponse::error(trans("Internal server error"))->send();
        }
    }

    /**
     * @param Request $request
     * @param $package
     * @return JsonResponse
     */
    public function completed(Request $request, $package): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('complete', Package::class));
        $package = $this->packageRepository->select(['id'])->findOrFailById($package);
        $this->packageRepository->completed($package);
        $package = $this->packageRepository->select(['id', 'is_completed'])->findOrFailById($package->id);
        return ApiResponse::message(trans("Mission accomplished"))
            ->addData('package', new PackageResource($package))
            ->send();
    }

    /**
     * @param Request $request
     * @param $package
     * @return JsonResponse
     */
    public function uncompleted(Request $request, $package): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('complete', Package::class));
        $package = $this->packageRepository->select(['id'])->findOrFailById($package);
        $this->packageRepository->uncompleted($package);
        $package = $this->packageRepository->select(['id', 'is_completed'])->findOrFailById($package->id);
        return ApiResponse::message(trans("Mission accomplished"))
            ->addData('package', new PackageResource($package))
            ->send();
    }

    /**
     * @param Request $request
     * @param $package
     * @return JsonResponse
     */
    public function activeStatus(Request $request, $package): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('manage', Package::class));
        $package = $this->packageRepository->select(['id'])->findOrFailById($package);
        $this->packageRepository->changeStatus($package, PackageStatus::Active);
        $package = $this->packageRepository->select(['id', 'status'])->findOrFailById($package->id);
        return ApiResponse::message(trans("Mission accomplished"))
            ->addData('package', new PackageResource($package))
            ->send();
    }

    /**
     * @param Request $request
     * @param $package
     * @return JsonResponse
     */
    public function inactiveStatus(Request $request, $package): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('manage', Package::class));
        $package = $this->packageRepository->select(['id'])->findOrFailById($package);
        $this->packageRepository->changeStatus($package, PackageStatus::Inactive);
        $package = $this->packageRepository->select(['id', 'status'])->findOrFailById($package->id);
        return ApiResponse::message(trans("Mission accomplished"))
            ->addData('package', new PackageResource($package))
            ->send();
    }

    /**
     * Destroy a package.
     *
     * @param Request $request
     * @param $package
     * @return JsonResponse
     */
    public function destroy(Request $request, $package): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('delete', Package::class));
        $package = $this->packageRepository->findOrFailById($package);
        $this->packageRepository->destroy($package);
        return ApiResponse::message(trans("The :attribute was successfully deleted", ['attribute' => trans('Package')]))->send();
    }

    #endregion
}
