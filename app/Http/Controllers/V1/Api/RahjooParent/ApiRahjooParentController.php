<?php

namespace App\Http\Controllers\V1\Api\RahjooParent;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Models\RahjooParent;
use App\Services\V1\RahjooParent\RahjooParentService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ApiRahjooParentController extends ApiBaseController
{
    private RahjooParentService $rahjooParent;

    /**
     * @param RahjooParentService $rahjooParent
     */
    public function __construct(RahjooParentService $rahjooParent)
    {
        $this->rahjooParent = $rahjooParent;
    }

    /**
     * Get rahjoo parents as pagination.
     *
     * @OA\Get (
     *     path="/rahjoo-parents",
     *     summary="لیست رهجوهایی که والدین دارند بصورت صفحه بندی",
     *     description="",
     *     tags={"والدین"},
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
        return $this->rahjooParent->index($request);
    }

    /**
     * Store a rahjoo parent.
     *
     * @OA\Post(
     *     path="/rahjoo-parents/{rahjoo_id}",
     *     summary="ثبت یا بروزرسانی والدین",
     *     description="اگر جنسیت وجود داشته باشد بروزرسانی وگرنه ثبت میشود",
     *     tags={"والدین"},
     *     @OA\Parameter(
     *         description="شناسه رهجو",
     *         in="path",
     *         name="rahjoo_id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="job_id",
     *                     type="number",
     *                     description="شناسه شغل"
     *                 ),
     *                 @OA\Property(
     *                     property="grade_id",
     *                     type="number",
     *                     description="شناسه مقطع تحصیلی"
     *                 ),
     *                 @OA\Property(
     *                     property="major_id",
     *                     type="number",
     *                     description="شناسه رشته تحصیلی"
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="نام"
     *                 ),
     *                 @OA\Property(
     *                     property="mobile",
     *                     type="string",
     *                     description="شماره موبایل"
     *                 ),
     *                 @OA\Property(
     *                     property="birthdate",
     *                     type="string",
     *                     description="تاریخ تولد - نمونه: 1401/08/30"
     *                 ),
     *                 @OA\Property(
     *                     property="gender",
     *                     type="string",
     *                     enum={"Male","Female"},
     *                     description="جنسیت: Male: مرد Female: زن",
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
    public function store(Request $request, $rahjoo)
    {
        return $this->rahjooParent->store($request, $rahjoo);
    }

    /**
     * Delete a rahjoo parent.
     *
     * @OA\Delete(
     *     path="/rahjoo-parents/{id}",
     *     summary="حذف والدین رهجو",
     *     description="",
     *     tags={"والدین"},
     *     @OA\Parameter(
     *         description="شناسه والدین",
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
    public function destroy(Request $request, $rahjooParent)
    {
        return $this->rahjooParent->destroy($request, $rahjooParent);
    }
}
