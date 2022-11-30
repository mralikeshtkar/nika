<?php

namespace App\Services\V1\Job;

use App\Http\Resources\V1\Job\JobResource;
use App\Http\Resources\V1\PaginationResource;
use App\Models\City;
use App\Models\Job;
use App\Repositories\V1\Job\Interfaces\JobRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JobService extends BaseService
{
    /**
     * @var JobRepositoryInterface
     */
    private JobRepositoryInterface $jobRepository;

    #region Constructor

    public function __construct(JobRepositoryInterface $jobRepository)
    {
        $this->jobRepository = $jobRepository;
    }

    #endregion

    #region Public methods

    /**
     * Get jobs as pagination.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('index', Job::class));
        $jobs = $this->jobRepository->select(['id', 'name'])
            ->filterPagination($request)
            ->paginate($request->get('perPage', 10));
        $resource = PaginationResource::make($jobs)->additional(['itemsResource' => JobResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('jobs', $resource)
            ->send();
    }

    /**
     * Get all jobs.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function all(Request $request): JsonResponse
    {
        $jobs=$this->jobRepository->select(['id','name'])->all();
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('jobs', JobResource::collection($jobs))
            ->send();
    }

    /**
     * Store a job.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('create', Job::class));
        ApiResponse::validate($request->all(), [
            'name' => ['required', 'string', 'unique:' . Job::class . ',name'],
        ]);
        $job = $this->jobRepository->create([
            'user_id' => $request->user()->id,
            'name' => $request->name,
        ]);
        return ApiResponse::message(trans("The :attribute was successfully registered", ['attribute' => trans('Job')]), Response::HTTP_CREATED)
            ->addData('job', JobResource::make($job))
            ->send();
    }

    /**
     * Update a job.
     *
     * @param Request $request
     * @param $job
     * @return JsonResponse
     */
    public function update(Request $request, $job): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('edit', Job::class));
        $job = $this->jobRepository->findOrFailById($job);
        ApiResponse::validate($request->all(), [
            'name' => ['required', 'string', 'unique:' . Job::class . ',name,' . $job->id],
        ]);
        $job = $this->jobRepository->update($job, [
            'name' => $request->name,
        ]);
        return ApiResponse::message(trans("The :attribute was successfully updated", ['attribute' => trans('Job')]))
            ->addData('city', JobResource::make($job))
            ->send();
    }

    /**
     * Destroy a job.
     *
     * @param Request $request
     * @param $job
     * @return JsonResponse
     */
    public function destroy(Request $request, $job): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('delete', Job::class));
        $job = $this->jobRepository->findOrFailById($job);
        $this->jobRepository->destroy($job);
        return ApiResponse::message(trans("The :attribute was successfully deleted", ['attribute' => trans('Job')]))->send();
    }

    #endregion
}
