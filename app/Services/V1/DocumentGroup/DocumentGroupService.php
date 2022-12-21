<?php

namespace App\Services\V1\DocumentGroup;

use App\Http\Resources\V1\DocumentGroup\DocumentGroupResource;
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
    public function store(Request $request): JsonResponse
    {
        ApiResponse::validate($request->all(), [
            'title' => ['required', 'string', 'unique:' . DocumentGroup::class . ',title'],
            'description' => ['nullable', 'string'],
        ]);
        $documentGroup = $this->documentGroupRepository->create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'description' => $request->description,
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
        $documentGroup = $this->documentGroupRepository->findOrFailById($documentGroup);
        ApiResponse::validate($request->all(), [
            'title' => ['required', 'string', 'unique:' . DocumentGroup::class . ',title,' . $documentGroup->id],
            'description' => ['nullable', 'string'],
        ]);
        $documentGroup = $this->documentGroupRepository->update($documentGroup, [
            'title' => $request->title,
            'description' => $request->description,
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
        $documentGroup = $this->documentGroupRepository->findOrFailById($documentGroup);
        $this->documentGroupRepository->destroy($documentGroup);
        return ApiResponse::message(trans("The :attribute was successfully deleted", ['attribute' => trans('DocumentGroup')]))->send();
    }

    #endregion
}
