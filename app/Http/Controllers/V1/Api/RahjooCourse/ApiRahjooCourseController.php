<?php

namespace App\Http\Controllers\V1\Api\RahjooCourse;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\RahjooCourse\RahjooCourseService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ApiRahjooCourseController extends ApiBaseController
{
    private RahjooCourseService $rahjooCourseService;

    /**
     * @param RahjooCourseService $rahjooCourseService
     */
    public function __construct(RahjooCourseService $rahjooCourseService)
    {
        $this->rahjooCourseService = $rahjooCourseService;
    }

    /**
     * Get all rahjoo courses with rahjoo.
     *
     * @OA\Get (
     *     path="/rahjoo-courses",
     *     summary="لیست دوره های رهجو به همراه رهجو بصورت صفحه بندی",
     *     description="",
     *     tags={"دوره های رهجو"},
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
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function index(Request $request)
    {
        return $this->rahjooCourseService->index($request);
    }

    /**
     * Store rahjoo course.
     *
     * @OA\Post(
     *     path="/rahjoo-courses/{id}",
     *     summary="ثبت دوره های رهجو",
     *     description="",
     *     tags={"دوره های رهجو"},
     *     @OA\Parameter(
     *         description="شناسه رهجو",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(
     *                     property="courses",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="name", type="string"),
     *                         @OA\Property(property="duration", type="number"),
     *                      ),
     *                     description="دوره های رهجو - مدت زمان دوره باید بصورت دقیقه باشد"
     *                 )
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
    public function store(Request $request, $rahjoo)
    {
        return $this->rahjooCourseService->store($request, $rahjoo);
    }

    /**
     * Delete a rahjoo course.
     *
     * @OA\Delete(
     *     path="/rahjoo-courses/{id}",
     *     summary="حذف دوره های رهجو",
     *     description="",
     *     tags={"دوره های رهجو"},
     *     @OA\Parameter(
     *         description="شناسه دوره رهجو",
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
    public function destroy(Request $request, $rahjooCourse)
    {
        return $this->rahjooCourseService->destroy($request, $rahjooCourse);
    }
}
