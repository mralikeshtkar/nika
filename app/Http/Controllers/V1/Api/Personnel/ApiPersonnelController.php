<?php

namespace App\Http\Controllers\V1\Api\Personnel;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Personnel\PersonnelService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ApiPersonnelController extends ApiBaseController
{
    /**
     * @var PersonnelService
     */
    private PersonnelService $personnelService;

    /**
     * @param PersonnelService $personnelService
     */
    public function __construct(PersonnelService $personnelService)
    {
        $this->personnelService = $personnelService;
    }

    /**
     * Get users with personnel information as pagination.
     *
     * @OA\Get (
     *     path="/personnels",
     *     summary="لیست پرسنل ها همراه با اطلاعات کاربری بصورت صفحه بندی",
     *     description="",
     *     tags={"پرسنل"},
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
        return $this->personnelService->index($request);
    }

    /**
     * Store a personnel for an user.
     *
     * @OA\Post(
     *     path="/personnels/{user_id}",
     *     summary="ثبت پرسنل",
     *     description="",
     *     tags={"پرسنل"},
     *     @OA\Parameter(
     *         description="شناسه کاربر",
     *         in="path",
     *         name="user_id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="major_id",
     *                     type="number",
     *                     description="شناسه رشته تحصیلی"
     *                 ),
     *                 @OA\Property(
     *                     property="job_id",
     *                     type="number",
     *                     description="شناسه شغل"
     *                 ),
     *                 @OA\Property(
     *                     property="birth_certificate_place_id",
     *                     type="number",
     *                     description="شناسه شهر محل تولد"
     *                 ),
     *                 @OA\Property(
     *                     property="is_married",
     *                     type="number",
     *                     enum={0,1},
     *                     description="وضعیت تاهل - 1: ازدواج کرده - 0: ازدواج نکرده"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="ایمیل"
     *                 ),
     *                 @OA\Property(
     *                     property="birth_certificate_number",
     *                     type="number",
     *                     description="شماره شناسنامه"
     *                 ),
     *                 @OA\Property(
     *                     property="language_level",
     *                     type="string",
     *                     enum={"low","mid","high"},
     *                     description="سطح زبان",
     *                 ),
     *                 @OA\Property(
     *                     property="computer_level",
     *                     type="string",
     *                     enum={"low","mid","high"},
     *                     description="سطح کامپیوتر",
     *                 ),
     *                 @OA\Property(
     *                     property="research_history",
     *                     type="string",
     *                     description="تاریخچه پژوهش",
     *                 ),
     *                 @OA\Property(
     *                     property="is_working",
     *                     type="bool",
     *                     description="آیا مشغول به کار است؟ - true: بله - false: خیر"
     *                 ),
     *                 @OA\Property(
     *                     property="work_description",
     *                     type="bool",
     *                     description="توضیحات کار"
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
    public function store(Request $request, $user)
    {
        return $this->personnelService->store($request, $user);
    }

    /**
     * Show a personnel.
     *
     * @OA\Get(
     *     path="/personnels/{user_id}",
     *     summary="دریافت کاربر همراه با اطلاعات پرسنلی",
     *     description="",
     *     tags={"پرسنل"},
     *     @OA\Parameter(
     *         description="شناسه کاربر",
     *         in="path",
     *         name="user_id",
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
    public function show(Request $request, $user)
    {
        return $this->personnelService->show($request, $user);
    }

    /**
     * Delete a personnel.
     *
     * @OA\Delete(
     *     path="/personnels/{user_id}",
     *     summary="حذف پرسنل",
     *     description="",
     *     tags={"پرسنل"},
     *     @OA\Parameter(
     *         description="شناسه کاربر",
     *         in="path",
     *         name="user_id",
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
    public function destroy(Request $request, $user)
    {
        return $this->personnelService->destroy($request, $user);
    }
}
