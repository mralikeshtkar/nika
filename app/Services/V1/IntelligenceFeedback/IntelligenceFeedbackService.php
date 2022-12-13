<?php

namespace App\Services\V1\IntelligenceFeedback;

use App\Http\Resources\V1\IntelligenceFeedback\IntelligenceFeedbackResource;
use App\Repositories\V1\IntelligenceFeedback\Interfaces\IntelligenceFeedbackRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class IntelligenceFeedbackService extends BaseService
{
    /**
     * @var IntelligenceFeedbackRepositoryInterface
     */
    private IntelligenceFeedbackRepositoryInterface $intelligenceFeedbackRepository;

    #region Constructor

    /**
     * @param IntelligenceFeedbackRepositoryInterface $intelligenceFeedbackRepository
     */
    public function __construct(IntelligenceFeedbackRepositoryInterface $intelligenceFeedbackRepository)
    {
        $this->intelligenceFeedbackRepository = $intelligenceFeedbackRepository;
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
            'package_id' => ['required', Rule::exists('intelligence_package', 'package_id')->where('intelligence_id', $request->intelligence_id)],
            'intelligence_id' => ['required', Rule::exists('intelligence_package', 'intelligence_id')->where('package_id', $request->package_id)],
            'title' => ['required', 'string'],
            'max_point' => ['required', 'numeric'],
        ]);
        $intelligenceFeedback = $this->intelligenceFeedbackRepository->create([
            'package_id' => $request->package_id,
            'intelligence_id' => $request->intelligence_id,
            'title' => $request->title,
            'max_point' => $request->max_point,
        ]);
        return ApiResponse::message(trans("The :attribute was successfully updated", ['attribute' => trans('IntelligenceFeedback')]))
            ->addData('intelligenceFeedback', new IntelligenceFeedbackResource($intelligenceFeedback))
            ->send();
    }

    #endregion
}
