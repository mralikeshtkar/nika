<?php

namespace App\Http\Controllers\V1\Api\Job;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Job\JobService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ApiJobController extends ApiBaseController
{
    /**
     * @var JobService
     */
    private JobService $jobService;

    /**
     * ApiJobController constructor.
     *
     * @param JobService $jobService
     */
    public function __construct(JobService $jobService)
    {
        $this->jobService = $jobService;
    }

    /**
     * Get jobs as pagination.
     *
     * @OA\Get (
     *     path="/jobs",
     *     summary="لیست شغل ها بصورت صفحه بندی",
     *     description="",
     *     tags={"شغل"},
     *     @OA\Parameter(
     *         description="شماره صفحه",
     *         in="query",
     *         name="page",
     *         required=true,
     *         example=1,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Parameter(
     *         description="تعداد نمایش در هر صفحه",
     *         in="query",
     *         name="perPage",
     *         example=10,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Parameter(
     *         description="جستجوی نام",
     *         in="query",
     *         name="name",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function index(Request $request)
    {
        return $this->jobService->index($request);
    }

    /**
     * Get jobs as pagination.
     *
     * @OA\Get (
     *     path="/jobs/all",
     *     summary="دریافت لیست شغل ها",
     *     description="",
     *     tags={"شغل"},
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function all(Request $request)
    {
        return $this->jobService->all($request);
    }

    /**
     * Store a job.
     *
     * @OA\Post(
     *     path="/jobs",
     *     summary="ثبت شغل",
     *     description="",
     *     tags={"شغل"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="نام"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="ثبت با موفقیت انجام شد",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function store(Request $request)
    {
        return $this->jobService->store($request);
    }

    /**
     * Update a jobs.
     *
     * @OA\Post(
     *     path="/jobs/{id}",
     *     summary="بروزرسانی شغل",
     *     description="",
     *     tags={"شغل"},
     *     @OA\Parameter(
     *         description="شناسه شغل",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"_method","name"},
     *                 @OA\Property(
     *                     property="_method",
     *                     type="string",
     *                     default="put",
     *                     enum={"put"},
     *                     description="این مقدار باید بصورت ثابت شود",
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="نام"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function update(Request $request,$job)
    {
        return $this->jobService->update($request,$job);
    }

    /**
     * Delete a job.
     *
     * @OA\Delete(
     *     path="/jobs/{id}",
     *     summary="حذف شغل",
     *     description="",
     *     tags={"شغل"},
     *     @OA\Parameter(
     *         description="شناسه شغل",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function destroy(Request $request, $job)
    {
        return $this->jobService->destroy($request,$job);
    }
}
