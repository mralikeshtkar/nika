<?php

namespace App\Http\Controllers\V1\Api\Package;

use App\Enums\Media\MediaExtension;
use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Package\PackageService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use OpenApi\Annotations as OA;

class ApiPackageController extends ApiBaseController
{
    /**
     * @var PackageService
     */
    private PackageService $packageService;

    /**
     * @param PackageService $packageService
     */
    public function __construct(PackageService $packageService)
    {
        $this->packageService = $packageService;
    }

    /**
     * Get packages as pagination.
     *
     * @OA\Get (
     *     path="/packages",
     *     summary="لیست پکیج ها بصورت صفحه بندی",
     *     description="",
     *     tags={"پکیج"},
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
     *         description="جستجوی عنوان",
     *         in="query",
     *         name="title",
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
        return $this->packageService->index($request);
    }

    /**
     * Show a package.
     *
     * @OA\Get(
     *     path="/packages/{id}",
     *     summary="نمایش اطلاعات پکیج",
     *     description="",
     *     tags={"پکیج"},
     *     @OA\Parameter(
     *         description="شناسه پکیج",
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
    public function show(Request $request, $package)
    {
        return $this->packageService->show($request, $package);
    }

    /**
     * Store a package.
     *
     * @OA\Post(
     *     path="/packages",
     *     summary="ثبت پکیج",
     *     description="[وضعیت تکمیل - بصورت boolean باید باشد][وضعیت Active:فعال Inactive:غیرفعال]",
     *     tags={"پکیج"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"title","age","price"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="عنوان"
     *                 ),
     *                 @OA\Property(
     *                     property="age",
     *                     type="number",
     *                     description="سن"
     *                 ),
     *                 @OA\Property(
     *                     property="price",
     *                     type="number",
     *                     description="قیمت"
     *                 ),
     *                 @OA\Property(
     *                     property="is_completed",
     *                     type="string",
     *                     enum={1,0},
     *                     description="وضعیت تکمیل - بصورت boolean باید باشد",
     *                 ),
     *                  @OA\Property(
     *                      property="video",
     *                      type="string",
     *                      description="ویدیو پکیج",
     *                      format="binary",
     *                  ),
     *                 @OA\Property(
     *                     property="status",
     *                     type="string",
     *                     enum={"Active","Inactive"},
     *                     description="وضعیت Active:فعال Inactive:غیرفعال",
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="توضیحات"
     *                 ),
     *                 @OA\Property(
     *                     property="product_id",
     *                     type="string",
     *                     description="شناسه محصول"
     *                 ),
     *                  @OA\Property(
     *                     property="intelligences[]",
     *                     type="array",
     *                     @OA\Items(type="number"),
     *                     description="هوش ها (شناسه هوش)"
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
    public function store(Request $request)
    {
        return $this->packageService->store($request);
    }

    /**
     * Store the video package.
     *
     * @OA\Post(
     *     path="/packages/{id}/upload-video",
     *     summary="اپلود ویدیو پکیج",
     *     description="",
     *     tags={"پکیج"},
     *     @OA\Parameter(
     *         description="شناسه پکیج",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"file"},
     *                  @OA\Property(
     *                      property="file",
     *                      type="string",
     *                      description="ویدیو پکیج",
     *                      format="binary",
     *                  ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="ثبت با موفقیت انجام شد",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function uploadVideo(Request $request, $package)
    {
        return $this->packageService->uploadVideo($request, $package);
    }

    /**
     * Update a package.
     *
     * @OA\Post(
     *     path="/packages/{id}",
     *     summary="بروزرسانی پکیج",
     *     description="",
     *     tags={"پکیج"},
     *     @OA\Parameter(
     *         description="شناسه پکیج",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"_method","title","age","price"},
     *                 @OA\Property(
     *                     property="_method",
     *                     type="string",
     *                     default="put",
     *                     enum={"put"},
     *                     description="این مقدار باید بصورت ثابت شود",
     *                 ),
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="عنوان"
     *                 ),
     *                 @OA\Property(
     *                     property="age",
     *                     type="number",
     *                     description="سن"
     *                 ),
     *                 @OA\Property(
     *                     property="price",
     *                     type="number",
     *                     description="قیمت"
     *                 ),
     *                 @OA\Property(
     *                     property="is_completed",
     *                     type="string",
     *                     enum={1,0},
     *                     description="وضعیت تکمیل - بصورت boolean باید باشد",
     *                 ),
     *                 @OA\Property(
     *                     property="status",
     *                     type="string",
     *                     enum={"Active","Inactive"},
     *                     description="وضعیت Active:فعال Inactive:غیرفعال",
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="توضیحات"
     *                 ),
     *                 @OA\Property(
     *                     property="product_id",
     *                     type="string",
     *                     description="شناسه محصول"
     *                 ),
     *                  @OA\Property(
     *                     property="intelligences[]",
     *                     type="array",
     *                     @OA\Items(type="number"),
     *                     description="هوش ها (شناسه هوش)"
     *                 )
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
    public function update(Request $request, $package)
    {
        return $this->packageService->update($request, $package);
    }

    /**
     * Change status is completed package to true
     *
     * @OA\Put(
     *     path="/packages/{id}/completed",
     *     summary="تغییر وضعیت پکیج به تکمیل شده",
     *     description="",
     *     tags={"پکیج"},
     *     @OA\Parameter(
     *         description="شناسه پکیج",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="ثبت با موفقیت انجام شد",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function completed(Request $request, $package)
    {
        return $this->packageService->completed($request, $package);
    }

    /**
     * Change status is completed package to false
     *
     * @OA\Put(
     *     path="/packages/{id}/uncompleted",
     *     summary="تغییر وضعیت پکیج به تکمیل نشده",
     *     description="",
     *     tags={"پکیج"},
     *     @OA\Parameter(
     *         description="شناسه پکیج",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="ثبت با موفقیت انجام شد",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function uncompleted(Request $request, $package)
    {
        return $this->packageService->uncompleted($request, $package);
    }

    /**
     * Change status package to active
     *
     * @OA\Put(
     *     path="/packages/{id}/active-status",
     *     summary="تغییر وضعیت پکیج به فعال",
     *     description="",
     *     tags={"پکیج"},
     *     @OA\Parameter(
     *         description="شناسه پکیج",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="ثبت با موفقیت انجام شد",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function activeStatus(Request $request, $package)
    {
        return $this->packageService->activeStatus($request, $package);
    }

    /**
     * Change status package to active
     *
     * @OA\Put(
     *     path="/packages/{id}/inactive-status",
     *     summary="تغییر وضعیت پکیج به فعال",
     *     description="",
     *     tags={"پکیج"},
     *     @OA\Parameter(
     *         description="شناسه پکیج",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="ثبت با موفقیت انجام شد",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function inactiveStatus(Request $request, $package)
    {
        return $this->packageService->inactiveStatus($request, $package);
    }

    /**
     * @OA\Get(
     *     path="/packages/{id}/package-exercises-dont-have-priority",
     *     summary="لیست انتخاب هوش و تمرینات برای اولویت",
     *     description="",
     *     tags={"پکیج"},
     *     @OA\Parameter(
     *         description="شناسه پکیج",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Parameter(
     *         description="عنوان تمرین",
     *         in="query",
     *         name="exercise",
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function packageExercisesDontHavePriority(Request $request, $package)
    {
        return $this->packageService->packageExercisesDontHavePriority($request, $package);
    }

    /**
     * @OA\Get(
     *     path="/packages/{id}/exercise-priority-list",
     *     summary="لیست اولویت های تمرینات",
     *     description="",
     *     tags={"پکیج"},
     *     @OA\Parameter(
     *         description="شناسه پکیج",
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
    public function exercisePriority(Request $request, $intelligencePackage)
    {
        return $this->packageService->exercisePriority($request, $intelligencePackage);
    }

    /**
     * @OA\Post(
     *     path="/packages/{id}/exercise-priority-detach",
     *     summary="ثبت اولویت تمرین",
     *     description="",
     *     tags={"پکیج"},
     *     @OA\Parameter(
     *         description="شناسه پکیج",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"intelligence_id","exercise_id"},
     *                 @OA\Property(
     *                     property="intelligence_id",
     *                     type="string",
     *                     description="شناسه هوش"
     *                 ),
     *                 @OA\Property(
     *                     property="exercise_id",
     *                     type="number",
     *                     description="شناسه تمرین"
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
    public function storeExercisePriority(Request $request, $intelligencePackage)
    {
        return $this->packageService->storeExercisePriority($request, $intelligencePackage);
    }

    /**
     * @OA\Post(
     *     path="/packages/{id}/exercise-prioraity-detach",
     *     summary="حذف اولویت تمرین",
     *     description="",
     *     tags={"پکیج"},
     *     @OA\Parameter(
     *         description="شناسه پکیج",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"intelligence_id","exercise_id"},
     *                 @OA\Property(
     *                     property="intelligence_id",
     *                     type="number",
     *                     description="شناسه هوش"
     *                 ),
     *                 @OA\Property(
     *                     property="exercise_id",
     *                     type="number",
     *                     description="شناسه تمرین"
     *                 )
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
    public function destroyExercisePriority(Request $request, $intelligencePackage)
    {
        return $this->packageService->destroyExercisePriority($request, $intelligencePackage);
    }

    /**
     * @OA\Get(
     *     path="/packages/{id}/exercises",
     *     summary="تمرین های پکیج بصورت صفحه بندی",
     *     description="",
     *     tags={"پکیج"},
     *     @OA\Parameter(
     *         description="شناسه پکیج",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
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
    public function exercises(Request $request, $package)
    {
        return $this->packageService->exercises($request, $package);
    }

    /**
     * Delete a package.
     *
     * @OA\Delete(
     *     path="/packages/{id}",
     *     summary="حذف پکیج",
     *     description="",
     *     tags={"پکیج"},
     *     @OA\Parameter(
     *         description="شناسه پکیج",
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
    public function destroy(Request $request, $package)
    {
        return $this->packageService->destroy($request, $package);
    }

    public function checkDiscount(Request $request,$package)
    {
        return $this->packageService->checkDiscount($request, $package);
    }

    /**
     * @OA\Post (
     *     path="/packages/{id}/buy",
     *     summary="پکیج",
     *     description="",
     *     tags={"خرید پکیج"},
     *     @OA\Parameter(
     *         description="شناسه پکیج",
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
    public function buy(Request $request, $package)
    {
        return $this->packageService->buy($request, $package);
    }
}
