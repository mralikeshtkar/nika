<?php

namespace App\Services\V1\City;

use App\Http\Resources\V1\City\CityResource;
use App\Http\Resources\V1\PaginationResource;
use App\Http\Resources\V1\Province\ProvinceResource;
use App\Models\City;
use App\Models\Province;
use App\Repositories\V1\City\Interfaces\CityRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CityService extends BaseService
{
    /**
     * @var CityRepositoryInterface
     */
    private CityRepositoryInterface $cityRepository;

    #region Constructor

    /**
     * CityService constructor.
     *
     * @param CityRepositoryInterface $cityRepository
     */
    public function __construct(CityRepositoryInterface $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    #endregion

    #region Public methods

    /**
     * Get cities as pagination.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('index', City::class));
        $cities = $this->cityRepository->select(['id', 'name', 'province_id'])
            ->withProvinceName()
            ->filterPagination($request)
            ->paginate($request->get('perPage', 10));
        $resource = PaginationResource::make($cities)->additional(['itemsResource' => CityResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('cities', $resource)
            ->send();
    }

    /**
     * Store a city.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('create', City::class));
        ApiResponse::validate($request->all(), [
            'name' => ['required', 'string'],
            'province_id' => ['required', 'exists:' . Province::class . ',id'],
        ], [], [
            'province_id' => trans('Province'),
        ]);
        $city = $this->cityRepository->create([
            'user_id' => $request->user()->id,
            'province_id' => $request->province_id,
            'name' => $request->name,
        ]);
        return ApiResponse::message(trans("The :attribute was successfully registered", ['attribute' => trans('City')]), Response::HTTP_CREATED)
            ->addData('city', CityResource::make($city))
            ->send();
    }

    /**
     * Update a city.
     *
     * @param Request $request
     * @param $city
     * @return JsonResponse
     */
    public function update(Request $request, $city): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('edit', City::class));
        $city = $this->cityRepository->findOrFailById($city);
        ApiResponse::validate($request->all(), [
            'name' => ['required', 'string'],
            'province_id' => ['required', 'exists:' . Province::class . ',id'],
        ], [], [
            'province_id' => trans('Province'),
        ]);
        $city = $this->cityRepository->update($city, [
            'name' => $request->name,
            'province_id' => $request->province_id,
        ]);
        return ApiResponse::message(trans("The :attribute was successfully updated", ['attribute' => trans('City')]))
            ->addData('city', CityResource::make($city))
            ->send();
    }

    /**
     * Delete a city.
     *
     * @param Request $request
     * @param $city
     * @return JsonResponse
     */
    public function destroy(Request $request, $city): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('delete', City::class));
        $city = $this->cityRepository->findOrFailById($city);
        $this->cityRepository->destroy($city);
        return ApiResponse::message(trans("The :attribute was successfully deleted", ['attribute' => trans('City')]))->send();
    }

    #endregion

}
