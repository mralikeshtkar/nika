<?php

namespace App\Services\V1\IntelligencePointName;

use App\Http\Resources\V1\IntelligencePointName\IntelligencePointNameResource;
use App\Models\IntelligencePointName;
use App\Repositories\V1\IntelligencePointName\Interfaces\IntelligencePointNameRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
    public function store(Request $request): JsonResponse
    {
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
        $intelligencePointName = $this->intelligencePointNameRepository->findOrFailById($intelligencePointName);
        ApiResponse::validate($request->all(), [
            'name' => ['required', 'string', 'unique:' . IntelligencePointName::class . ',name,' . $intelligencePointName->id],
        ]);
        $intelligencePointName = $this->intelligencePointNameRepository->update($intelligencePointName, [
            'title' => $request->title,
        ]);
        return ApiResponse::message(trans("The :attribute was successfully updated", ['attribute' => trans('IntelligencePointName')]))
            ->addData('intelligencePointName', new IntelligencePointNameResource($intelligencePointName))
            ->send();
    }

    #endregion

}
