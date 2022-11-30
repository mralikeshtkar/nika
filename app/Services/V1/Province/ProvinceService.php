<?php

namespace App\Services\V1\Province;

use App\Http\Resources\V1\PaginationResource;
use App\Http\Resources\V1\Province\ProvinceResource;
use App\Models\Province;
use App\Repositories\V1\Province\Interfaces\ProvinceRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProvinceService extends BaseService
{
    /**
     * @var ProvinceRepositoryInterface
     */
    private ProvinceRepositoryInterface $provinceRepository;

    #region Constructor

    /**
     * ProvinceService constructor.
     *
     * @param ProvinceRepositoryInterface $provinceRepository
     */
    public function __construct(ProvinceRepositoryInterface $provinceRepository)
    {
        $this->provinceRepository = $provinceRepository;
    }

    #endregion

    #region Public methods

    /**
     * Get provinces as pagination.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('index', Province::class));
        $provinces = $this->provinceRepository->select(['id', 'name'])
            ->filterPagination($request)
            ->paginate($request->get('perPage', 10));
        $resource = PaginationResource::make($provinces)->additional(['itemsResource' => ProvinceResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('provinces', $resource)
            ->send();
    }

    /**
     * Get all provinces.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function all(Request $request): JsonResponse
    {
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('provinces', ProvinceResource::collection($this->provinceRepository->select(['id', 'name'])->getAll()))
            ->send();
    }

    /**
     * Show a province.
     *
     * @param Request $request
     * @param $province
     * @return JsonResponse
     */
    public function show(Request $request, $province): JsonResponse
    {
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('province', ProvinceResource::make($this->provinceRepository->with(['cities:id,province_id,name'])->findOrFailById($province)))->send();
    }

    /**
     * Store a province.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('create', Province::class));
        ApiResponse::validate($request->all(), [
            'name' => ['required', 'string',],
        ]);
        $province = $this->provinceRepository->create([
            'user_id' => $request->user()->id,
            'name' => $request->name,
        ]);
        return ApiResponse::message(trans("The :attribute was successfully registered", ['attribute' => trans('Province')]), Response::HTTP_CREATED)
            ->addData('province', ProvinceResource::make($province))
            ->send();
    }

    /**
     * Update a province.
     *
     * @param Request $request
     * @param $province
     * @return JsonResponse
     */
    public function update(Request $request, $province): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('edit', Province::class));
        ApiResponse::validate($request->all(), [
            'name' => ['required', 'string',],
        ]);
        $province = $this->provinceRepository->findOrFailById($province);
        $province = $this->provinceRepository->update($province, [
            'name' => $request->name,
        ]);
        return ApiResponse::message(trans("The :attribute was successfully updated", ['attribute' => trans('Province')]))
            ->addData('province', ProvinceResource::make($province))
            ->send();
    }

    /**
     * Destroy a province.
     *
     * @param Request $request
     * @param $province
     * @return JsonResponse
     */
    public function destroy(Request $request, $province): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('delete', Province::class));
        $province = $this->provinceRepository->findOrFailById($province);
        $this->provinceRepository->destroy($province);
        return ApiResponse::message(trans("The :attribute was successfully deleted", ['attribute' => trans('Province')]))->send();
    }

    #endregion
}
