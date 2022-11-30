<?php

namespace App\Services\V1\Major;

use App\Http\Resources\V1\Major\MajorResource;
use App\Http\Resources\V1\PaginationResource;
use App\Models\Major;
use App\Repositories\V1\Major\Interfaces\MajorRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MajorService extends BaseService
{
    /**
     * @var MajorRepositoryInterface
     */
    private MajorRepositoryInterface $majorRepository;

    #region Constructor

    /**
     * MajorService constructor.
     *
     * @param MajorRepositoryInterface $majorRepository
     */
    public function __construct(MajorRepositoryInterface $majorRepository)
    {
        $this->majorRepository = $majorRepository;
    }

    #endregion

    #region Public methods

    /**
     * Get all majors as pagination.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('index', Major::class));
        $majors=$this->majorRepository->select(['id', 'name'])
            ->filterPaginate($request)
            ->paginate($request->get('perPage', 10));
        $resource = PaginationResource::make($majors)->additional(['itemsResource' => MajorResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('majors', $resource)
            ->send();
    }

    /**
     * Get all majors.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function all(Request $request): JsonResponse
    {
        $majors=$this->majorRepository->select(['id', 'name'])->all();
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('majors', MajorResource::collection($majors))
            ->send();
    }

    /**
     * Store a major.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('create', Major::class));
        ApiResponse::validate($request->all(), [
            'name' => ['required', 'string'],
        ]);
        $major = $this->majorRepository->create([
            'user_id' => $request->user()->id,
            'name' => $request->name,
        ]);
        return ApiResponse::message(trans("The :attribute was successfully registered", ['attribute' => trans('Major')]), Response::HTTP_CREATED)
            ->addData('major', MajorResource::make($major))
            ->send();
    }

    /**
     * Update a major.
     *
     * @param $request
     * @param $major
     * @return JsonResponse
     */
    public function update($request, $major): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('edit', Major::class));
        $major = $this->majorRepository->findOrFailById($major);
        ApiResponse::validate($request->all(), [
            'name' => ['required', 'string'],
        ]);
        $major = $this->majorRepository->update($major, [
            'name' => $request->name,
        ]);
        return ApiResponse::message(trans("The :attribute was successfully updated", ['attribute' => trans('Major')]))
            ->addData('city', MajorResource::make($major))
            ->send();
    }

    /**
     * Destroy a major.
     *
     * @param Request $request
     * @param $major
     * @return JsonResponse
     */
    public function destroy(Request $request, $major): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('delete', Major::class));
        $major = $this->majorRepository->findOrFailById($major);
        $this->majorRepository->destroy($major);
        return ApiResponse::message(trans("The :attribute was successfully deleted", ['attribute' => trans('Major')]))->send();
    }

    #endregion
}
