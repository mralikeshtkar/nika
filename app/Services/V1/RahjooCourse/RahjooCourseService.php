<?php

namespace App\Services\V1\RahjooCourse;

use App\Http\Resources\V1\City\CityResource;
use App\Http\Resources\V1\PaginationResource;
use App\Http\Resources\V1\RahjooCourse\RahjooCourseResource;
use App\Models\RahjooCourse;
use App\Repositories\V1\Rahjoo\Interfaces\RahjooRepositoryInterface;
use App\Repositories\V1\RahjooCourse\Interfaces\RahjooCourseRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RahjooCourseService extends BaseService
{
    #region Constance

    private RahjooCourseRepositoryInterface $rahjooCourseRepository;

    /**
     * RahjooCourseService constructor.
     *
     * @param RahjooCourseRepositoryInterface $rahjooCourseRepository
     */
    public function __construct(RahjooCourseRepositoryInterface $rahjooCourseRepository)
    {
        $this->rahjooCourseRepository = $rahjooCourseRepository;
    }

    #endregion

    #region Public methods

    /**
     * Get all rahjoo courses with rahjoo.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('index', RahjooCourse::class));
        $rahjoo_courses = $this->rahjooCourseRepository->select(['id', 'rahjoo_id', 'name', 'duration'])
            ->with(['rahjoo'])
            ->paginate($request->get('perPage', 10));
        $resource = PaginationResource::make($rahjoo_courses)->additional(['itemsResource' => RahjooCourseResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('cities', $resource)
            ->send();
    }

    /**
     * Store Rahjoo course.
     *
     * @param Request $request
     * @param $rahjoo
     * @return JsonResponse
     */
    public function store(Request $request, $rahjoo): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('create', RahjooCourse::class));
        $rahjooRepository = resolve(RahjooRepositoryInterface::class);
        $rahjoo = $rahjooRepository->findOrFailById($rahjoo);
        ApiResponse::validate($request->all(), [
            'courses' => ['required', 'array', 'min:1'],
            'courses.*.name' => ['required', 'string'],
            'courses.*.duration' => ['required', 'numeric', 'min:0'],
        ]);
        //todo remove line 45
        RahjooCourse::query()->truncate();
        $rahjooCourses = $rahjooRepository->storeManyCourses($rahjoo, $request->courses, $request->user()->id);
        return ApiResponse::message(trans("The :attribute was successfully registered", ['attribute' => trans('RahjooCourse')]), Response::HTTP_CREATED)
            ->addData('courses', RahjooCourseResource::collection($rahjooCourses))
            ->send();
    }

    /**
     * Destroy a rahjoo course.
     *
     * @param Request $request
     * @param $rahjooCourse
     * @return JsonResponse
     */
    public function destroy(Request $request, $rahjooCourse): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('delete', RahjooCourse::class));
        $rahjooCourse = $this->rahjooCourseRepository->findOrFailById($rahjooCourse);
        $this->rahjooCourseRepository->destroy($rahjooCourse);
        return ApiResponse::message(trans("The :attribute was successfully deleted", ['attribute' => trans('RahjooCourse')]))->send();
    }

    #endregion
}
