<?php

namespace App\Services\V1\IntelligencePoint;

use App\Http\Resources\V1\IntelligencePoint\IntelligencePointResource;
use App\Models\Intelligence;
use App\Models\IntelligencePointName;
use App\Models\Package;
use App\Repositories\V1\Intelligence\Interfaces\IntelligenceRepositoryInterface;
use App\Repositories\V1\IntelligencePoint\Interfaces\IntelligencePointRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IntelligencePointService extends BaseService
{
    /**
     * @var IntelligencePointRepositoryInterface
     */
    private IntelligencePointRepositoryInterface $intelligencePointRepository;

    #region Constructor

    /**
     * @param IntelligencePointRepositoryInterface $intelligencePointRepository
     */
    public function __construct(IntelligencePointRepositoryInterface $intelligencePointRepository)
    {
        $this->intelligencePointRepository = $intelligencePointRepository;
    }

    #endregion

    #region public methods

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        ApiResponse::validate($request->all(), [
            'intelligence_id' => ['required', 'exists:' . Intelligence::class . ',id'],
            'intelligence_point_name_id' => ['required', 'exists:' . IntelligencePointName::class . ',id'],
            'max_point' => ['required', 'numeric', 'min:0'],
        ]);
        $intelligencePoint = $this->intelligencePointRepository->create([
            'user_id' => $request->user()->id,
            'intelligence_id' => $request->intelligence_id,
            'intelligence_point_name_id' => $request->intelligence_point_name_id,
            'max_point' => $request->max_point,
        ]);
        return ApiResponse::message(trans("The :attribute was successfully registered", ['attribute' => trans('IntelligencePoint')]), Response::HTTP_CREATED)
            ->addData('intelligencePoint', new IntelligencePointResource($intelligencePoint))
            ->send();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function storeMultiple(Request $request): JsonResponse
    {
        ApiResponse::validate($request->all(), [
            'intelligence_id' => ['required', 'exists:' . Intelligence::class . ',id'],
            'points' => ['required', 'array', 'min:1'],
            'points.*.intelligence_point_name_id' => ['exists:' . IntelligencePointName::class . ',id'],
            'points.*.max_point' => ['numeric', 'min:0'],
        ]);
        $intelligenceRepository = resolve(IntelligenceRepositoryInterface::class);
        $intelligence = $intelligenceRepository->select(['id'])
            ->findOrFailById($request->intelligence_id);
        $intelligenceRepository->createMultiplePoints($intelligence, $request->points);
        return ApiResponse::message(trans("The :attribute was successfully registered", ['attribute' => trans('IntelligencePoint')]), Response::HTTP_CREATED)->send();
    }

    /**
     * @param Request $request
     * @param $intelligencePoint
     * @return JsonResponse
     */
    public function update(Request $request, $intelligencePoint): JsonResponse
    {
        $intelligencePoint = $this->intelligencePointRepository
            ->select(['id', 'user_id', 'intelligence_id', 'intelligence_point_name_id', 'package_id', 'max_point',])
            ->findOrFailById($intelligencePoint);
        ApiResponse::validate($request->all(), [
            'intelligence_id' => ['required', 'exists:' . Intelligence::class . ',id'],
            'intelligence_point_name_id' => ['required', 'exists:' . IntelligencePointName::class . ',id'],
            'max_point' => ['required', 'numeric', 'min:0'],
        ]);
        $intelligencePoint = $this->intelligencePointRepository->update($intelligencePoint, [
            'intelligence_id' => $request->intelligence_id,
            'intelligence_point_name_id' => $request->intelligence_point_name_id,
            'max_point' => $request->max_point,
        ]);
        return ApiResponse::message(trans("The :attribute was successfully updated", ['attribute' => trans('IntelligencePoint')]))
            ->addData('city', new IntelligencePointResource($intelligencePoint))
            ->send();
    }

    /**
     * @param Request $request
     * @param $intelligencePoint
     * @return JsonResponse
     */
    public function destroy(Request $request, $intelligencePoint): JsonResponse
    {
        $intelligencePoint = $this->intelligencePointRepository
            ->select(['id', 'user_id', 'intelligence_id', 'intelligence_point_name_id', 'package_id', 'max_point',])
            ->findOrFailById($intelligencePoint);
        $this->intelligencePointRepository->destroy($intelligencePoint);
        return ApiResponse::message(trans("The :attribute was successfully deleted", ['attribute' => trans('IntelligencePoint')]))->send();
    }

    #endregion

}
