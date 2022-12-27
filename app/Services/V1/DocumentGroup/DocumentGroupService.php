<?php

namespace App\Services\V1\DocumentGroup;

use App\Http\Resources\V1\DocumentGroup\DocumentGroupResource;
use App\Http\Resources\V1\PaginationResource;
use App\Models\DocumentGroup;
use App\Repositories\V1\DocumentGroup\Interfaces\DocumentGroupRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DocumentGroupService extends BaseService
{
    /**
     * @var DocumentGroupRepositoryInterface
     */
    private DocumentGroupRepositoryInterface $documentGroupRepository;

    #region Constructor

    /**
     * @param DocumentGroupRepositoryInterface $documentGroupRepository
     */
    public function __construct(DocumentGroupRepositoryInterface $documentGroupRepository)
    {
        $this->documentGroupRepository = $documentGroupRepository;
    }

    #endregion

    #region Public methods

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('index', DocumentGroup::class));
        $cities = $this->documentGroupRepository->select(['id', 'title', 'description'])
            ->filterPagination($request)
            ->paginate($request->get('perPage', 10));
        $resource = PaginationResource::make($cities)->additional(['itemsResource' => DocumentGroupResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('documentGroups', $resource)
            ->send();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('create', DocumentGroup::class));
        ApiResponse::validate($request->all(), [
            'title' => ['required', 'string', 'unique:' . DocumentGroup::class . ',title'],
            'description' => ['nullable', 'string'],
            'format' => ['required', 'string'],
        ]);
        $documentGroup = $this->documentGroupRepository->create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'description' => $request->description,
            'format' => $request->get('format'),
        ]);
        return ApiResponse::message(trans("The :attribute was successfully registered", ['attribute' => trans('DocumentGroup')]), Response::HTTP_CREATED)
            ->addData('documentGroup', new DocumentGroupResource($documentGroup))
            ->send();
    }

    /**
     * @param Request $request
     * @param $documentGroup
     * @return JsonResponse
     */
    public function update(Request $request, $documentGroup): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('edit', DocumentGroup::class));
        $documentGroup = $this->documentGroupRepository->findOrFailById($documentGroup);
        ApiResponse::validate($request->all(), [
            'title' => ['required', 'string', 'unique:' . DocumentGroup::class . ',title,' . $documentGroup->id],
            'description' => ['nullable', 'string'],
            'format' => ['required', 'string'],
        ]);
        $documentGroup = $this->documentGroupRepository->update($documentGroup, [
            'title' => $request->title,
            'description' => $request->description,
            'format' => $request->get('format'),
        ]);
        return ApiResponse::message(trans("The :attribute was successfully updated", ['attribute' => trans('DocumentGroup')]))
            ->addData('documentGroup', new DocumentGroupResource($documentGroup))
            ->send();
    }

    /**
     * @param Request $request
     * @param $documentGroup
     * @return JsonResponse
     */
    public function destroy(Request $request, $documentGroup): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('delete', DocumentGroup::class));
        $documentGroup = $this->documentGroupRepository->findOrFailById($documentGroup);
        $this->documentGroupRepository->destroy($documentGroup);
        return ApiResponse::message(trans("The :attribute was successfully deleted", ['attribute' => trans('DocumentGroup')]))->send();
    }

    #endregion
}
