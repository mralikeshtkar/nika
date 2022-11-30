<?php

namespace App\Services\V1\Grade;

use App\Http\Resources\V1\Grade\GradeResource;
use App\Http\Resources\V1\PaginationResource;
use App\Models\Grade;
use App\Repositories\V1\Grade\Interfaces\GradeRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GradeService extends BaseService
{
    private GradeRepositoryInterface $gradeRepository;

    #region Constructor

    /**
     * GradeService constructor.
     *
     * @param GradeRepositoryInterface $gradeRepository
     */
    public function __construct(GradeRepositoryInterface $gradeRepository)
    {
        $this->gradeRepository = $gradeRepository;
    }

    #endregion

    #region Public methods

    /**
     * Get grades as pagination.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('index', Grade::class));
        $grades = $this->gradeRepository->select(['id', 'name'])
            ->filterPagination($request)
            ->paginate($request->get('perPage', 10));
        $resource = PaginationResource::make($grades)->additional(['itemsResource' => GradeResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('cities', $resource)
            ->send();
    }

    /**
     * Store a grade.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('create', Grade::class));
        ApiResponse::validate($request->all(), [
            'name' => ['required', 'string'],
        ]);
        $grade = $this->gradeRepository->create([
            'name' => $request->name,
        ]);
        return ApiResponse::message(trans("The :attribute was successfully registered", ['attribute' => trans('Grade')]), Response::HTTP_CREATED)
            ->addData('grade', GradeResource::make($grade))
            ->send();
    }

    /**
     * Update a grade.
     *
     * @param Request $request
     * @param $grade
     * @return JsonResponse
     */
    public function update(Request $request, $grade): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('edit', Grade::class));
        $grade = $this->gradeRepository->findOrFailById($grade);
        ApiResponse::validate($request->all(), [
            'name' => ['required', 'string'],
        ]);
        $grade = $this->gradeRepository->update($grade, [
            'name' => $request->name,
        ]);
        return ApiResponse::message(trans("The :attribute was successfully updated", ['attribute' => trans('Grade')]))
            ->addData('grade', GradeResource::make($grade))
            ->send();
    }

    /**
     * Delete a grade.
     *
     * @param Request $request
     * @param $grade
     * @return JsonResponse
     */
    public function destroy(Request $request, $grade): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('delete', Grade::class));
        $grade = $this->gradeRepository->findOrFailById($grade);
        $this->gradeRepository->destroy($grade);
        return ApiResponse::message(trans("The :attribute was successfully deleted", ['attribute' => trans('Grade')]))->send();
    }

    #endregion
}
