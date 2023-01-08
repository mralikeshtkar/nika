<?php

namespace App\Services\V1\IntelligenceFeedback;

use App\Http\Resources\V1\IntelligenceFeedback\IntelligenceFeedbackResource;
use App\Http\Resources\V1\PaginationResource;
use App\Models\Intelligence;
use App\Models\IntelligenceFeedback;
use App\Models\IntelligencePackage;
use App\Repositories\V1\Intelligence\Interfaces\IntelligenceRepositoryInterface;
use App\Repositories\V1\IntelligenceFeedback\Interfaces\IntelligenceFeedbackRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

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
    public function index(Request $request): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('index', IntelligenceFeedback::class));
        $intelligenceFeedbacks = $this->intelligenceFeedbackRepository->select(['id', 'intelligence_package_id', 'title', 'max_point',])
            ->filterPagination($request)
            ->paginate($request->get('perPage', 10));
        $resource = PaginationResource::make($intelligenceFeedbacks)->additional(['itemsResource' => IntelligenceFeedbackResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('intelligencePoints', $resource)
            ->send();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('create', IntelligenceFeedback::class));
        ApiResponse::validate($request->all(), [
            'intelligence_package_id' => ['required', 'exists:' . IntelligencePackage::class . ',pivot_id'],
            'title' => ['required', 'string'],
            'max_point' => ['required', 'numeric'],
        ]);
        $intelligenceFeedback = $this->intelligenceFeedbackRepository->create([
            'user_id' => $request->user()->id,
            'intelligence_package_id' => $request->intelligence_package_id,
            'title' => $request->title,
            'max_point' => $request->max_point,
        ]);
        return ApiResponse::message(trans("The :attribute was successfully registered", ['attribute' => trans('IntelligenceFeedback')]), Response::HTTP_CREATED)
            ->addData('intelligenceFeedback', new IntelligenceFeedbackResource($intelligenceFeedback))
            ->send();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function storeMultiple(Request $request): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('create', IntelligenceFeedback::class));
        ApiResponse::validate($request->all(), [
            'intelligence_package_id' => ['required', 'exists:' . IntelligencePackage::class . ',pivot_id'],
            'feedbacks' => ['required', 'array', 'min:1'],
            'feedbacks.*.title' => ['string'],
            'feedbacks.*.max_point' => ['numeric', 'min:0'],
        ]);
        $intelligenceRepository = resolve(IntelligenceRepositoryInterface::class);
        $intelligence = $intelligenceRepository->select(['id'])
            ->findOrFailById($request->intelligence_id);
        $intelligenceRepository->createMultipleFeedbacks($intelligence, $request->feedbacks);
        return ApiResponse::message(trans("The :attribute was successfully updated", ['attribute' => trans('IntelligenceFeedback')]))
            ->addData('intelligenceFeedback', new IntelligenceFeedbackResource($intelligenceFeedback))
            ->send();
    }

    /**
     * @param Request $request
     * @param $intelligenceFeedback
     * @return JsonResponse
     */
    public function update(Request $request, $intelligenceFeedback): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('edit', IntelligenceFeedback::class));
        $intelligenceFeedback = $this->intelligenceFeedbackRepository->findOrFailById($intelligenceFeedback);
        ApiResponse::validate($request->all(), [
            'title' => ['required', 'string'],
            'max_point' => ['required', 'numeric', 'min:0'],
        ]);
        $intelligenceFeedback = $this->intelligenceFeedbackRepository->update($intelligenceFeedback, [
            'title' => $request->title,
            'max_point' => $request->max_point,
        ]);
        return ApiResponse::message(trans("The :attribute was successfully updated", ['attribute' => trans('IntelligenceFeedback')]))
            ->addData('intelligenceFeedback', new IntelligenceFeedbackResource($intelligenceFeedback->only(['id', 'title'])))
            ->send();
    }

    /**
     * @param Request $request
     * @param $intelligenceFeedback
     * @return JsonResponse
     */
    public function destroy(Request $request, $intelligenceFeedback): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('delete', IntelligenceFeedback::class));
        $intelligenceFeedback = $this->intelligenceFeedbackRepository->findOrFailById($intelligenceFeedback);
        $this->intelligenceFeedbackRepository->destroy($intelligenceFeedback);
        return ApiResponse::message(trans("The :attribute was successfully deleted", ['attribute' => trans('IntelligenceFeedback')]))->send();
    }

    #endregion
}
