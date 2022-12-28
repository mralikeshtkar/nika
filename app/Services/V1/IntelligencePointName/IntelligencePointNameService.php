<?php

namespace App\Services\V1\IntelligencePointName;

use App\Http\Resources\V1\IntelligencePointName\IntelligencePointNameResource;
use App\Http\Resources\V1\PaginationResource;
use App\Models\IntelligencePointName;
use App\Repositories\V1\IntelligencePointName\Interfaces\IntelligencePointNameRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IntelligencePointNameService extends BaseService
{
    /**
     * @var IntelligencePointNameRepositoryInterface
     */
    private IntelligencePointNameRepositoryInterface $intelligencePointNameRepository;

    #region Constructor

    /**
     * @param IntelligencePointNameRepositoryInterface $intelligencePointNameRepository
     */
    public function __construct(IntelligencePointNameRepositoryInterface $intelligencePointNameRepository)
    {
        $this->intelligencePointNameRepository = $intelligencePointNameRepository;
    }

    #endregion

    #region Public methods

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('index', IntelligencePointName::class));
        $intelligencePointNames = $this->intelligencePointNameRepository->select(['id', 'name'])
            ->filterPagination($request)
            ->paginate($request->get('perPage', 10));
        $resource = PaginationResource::make($intelligencePointNames)->additional(['itemsResource' => IntelligencePointNameResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('intelligencePointNames', $resource)
            ->send();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function all(Request $request): JsonResponse
    {
        $intelligencePointNames = $this->intelligencePointNameRepository->select(['id', 'name'])
            ->filterName($request->name)
            ->get();
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('intelligencePointNames', IntelligencePointNameResource::collection($intelligencePointNames))
            ->send();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('delete', IntelligencePointName::class));
        ApiResponse::validate($request->all(), [
            'name' => ['required', 'string', 'unique:' . IntelligencePointName::class . ',name'],
        ]);
        $intelligencePointName = $this->intelligencePointNameRepository->create([
            'user_id' => $request->user()->id,
            'name' => $request->name,
        ]);
        return ApiResponse::message(trans("The :attribute was successfully registered", ['attribute' => trans('IntelligencePointName')]), Response::HTTP_CREATED)
            ->addData('intelligencePointName', new IntelligencePointNameResource($intelligencePointName))
            ->send();
    }

    /**
     * @param Request $request
     * @param $intelligencePointName
     * @return JsonResponse
     */
    public function update(Request $request, $intelligencePointName): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('edit', IntelligencePointName::class));
        $intelligencePointName = $this->intelligencePointNameRepository->findOrFailById($intelligencePointName);
        ApiResponse::validate($request->all(), [
            'name' => ['required', 'string', 'unique:' . IntelligencePointName::class . ',name,' . $intelligencePointName->id],
        ]);
        $intelligencePointName = $this->intelligencePointNameRepository->update($intelligencePointName, [
            'name' => $request->name,
        ]);
        $intelligencePointName = $this->intelligencePointNameRepository->select(['id','name'])
            ->findOrFailById($intelligencePointName);
        return ApiResponse::message(trans("The :attribute was successfully updated", ['attribute' => trans('IntelligencePointName')]))
            ->addData('intelligencePointName', new IntelligencePointNameResource($intelligencePointName))
            ->send();
    }

    /**
     * @param Request $request
     * @param $intelligencePointName
     * @return JsonResponse
     */
    public function destroy(Request $request, $intelligencePointName): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('delete', IntelligencePointName::class));
        $intelligencePointName = $this->intelligencePointNameRepository->findOrFailById($intelligencePointName);
        $this->intelligencePointNameRepository->destroy($intelligencePointName);
        return ApiResponse::message(trans("The :attribute was successfully deleted", ['attribute' => trans('IntelligencePointName')]))->send();
    }

    #endregion

}
