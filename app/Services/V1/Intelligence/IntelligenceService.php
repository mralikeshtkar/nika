<?php

namespace App\Services\V1\Intelligence;

use App\Http\Resources\V1\Intelligence\IntelligenceResource;
use App\Http\Resources\V1\IntelligenceFeedback\IntelligenceFeedbackResource;
use App\Http\Resources\V1\IntelligencePoint\IntelligencePointResource;
use App\Http\Resources\V1\PaginationResource;
use App\Models\Intelligence;
use App\Repositories\V1\Intelligence\Interfaces\IntelligenceRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IntelligenceService extends BaseService
{
    /**
     * @var IntelligenceRepositoryInterface
     */
    private IntelligenceRepositoryInterface $intelligenceRepository;

    #region constructor.

    /**
     * @param IntelligenceRepositoryInterface $intelligenceRepository
     */
    public function __construct(IntelligenceRepositoryInterface $intelligenceRepository)
    {
        $this->intelligenceRepository = $intelligenceRepository;
    }

    #endregion

    #region Public methods

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('index', Intelligence::class));
        $intelligences = $this->intelligenceRepository->select(['id', 'title'])
            ->filterPagination($request)
            ->paginate($request->get('perPage', 10));
        $resource = PaginationResource::make($intelligences)->additional(['itemsResource' => IntelligenceResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('intelligences', $resource)
            ->send();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function all(Request $request): JsonResponse
    {
        $intelligences = $this->intelligenceRepository
            ->select(['id','title'])
            ->searchTitle($request)
            ->get();
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('intelligences', IntelligenceResource::collection($intelligences))
            ->send();
    }

    /**
     * @param Request $request
     * @param $intelligence
     * @return JsonResponse
     */
    public function show(Request $request, $intelligence): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('show', Intelligence::class));
        $intelligence = $this->intelligenceRepository->select(['id','title'])
            ->findOrFailById($intelligence);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('intelligence', new IntelligenceResource($intelligence))
            ->send();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('create', Intelligence::class));
        ApiResponse::validate($request->all(), [
            'title' => ['required', 'string'],
        ]);
        $intelligence = $this->intelligenceRepository->create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
        ]);
        return ApiResponse::message(trans("The :attribute was successfully registered", ['attribute' => trans('Intelligence')]), Response::HTTP_CREATED)
            ->addData('intelligence', new IntelligenceResource($intelligence))
            ->send();
    }

    /**
     * @param Request $request
     * @param $intelligence
     * @return JsonResponse
     */
    public function update(Request $request, $intelligence): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('edit', Intelligence::class));
        $intelligence = $this->intelligenceRepository->findOrFailById($intelligence);
        ApiResponse::validate($request->all(), [
            'title' => ['required', 'string'],
        ]);
        $intelligence = $this->intelligenceRepository->update($intelligence, [
            'title' => $request->title,
        ]);
        return ApiResponse::message(trans("The :attribute was successfully updated", ['attribute' => trans('Intelligence')]))
            ->addData('intelligence', new IntelligenceResource($intelligence))
            ->send();
    }

    /**
     * @param Request $request
     * @param $intelligence
     * @return JsonResponse
     */
    public function destroy(Request $request, $intelligence): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('delete', Intelligence::class));
        $intelligence = $this->intelligenceRepository->findOrFailById($intelligence);
        $this->intelligenceRepository->destroy($intelligence);
        return ApiResponse::message(trans("The :attribute was successfully deleted", ['attribute' => trans('Intelligence')]))->send();
    }

    /**
     * @param Request $request
     * @param $intelligence
     * @return JsonResponse
     */
    public function rahnama(Request $request, $intelligence): JsonResponse
    {
        $intelligence = $this->intelligenceRepository->select(['id'])
            ->with(['rahnama'])
            ->findOrFailById($intelligence);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('intelligence', new IntelligenceResource($intelligence))
            ->send();
    }

    #endregion
}
