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
     * Get package intelligences as pagination.
     *
     * @OA\Get (
     *     path="/packages/{id}/intelligences",
     *     summary="لیست هوش های پکیج بصورت صفحه بندی",
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
    public function intelligences(Request $request, $package)
    {
        return $this->packageService->intelligences($request, $package);
    }

    /**
     * Store a package.
     *
     * @OA\Post(
     *     path="/packages",
     *     summary="ثبت پکیج",
     *     description="",
     *     tags={"پکیج"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
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
     *                 required={"video"},
     *                  @OA\Property(
     *                      property="video",
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
     * Change status is completed package intelligence to true
     *
     * @OA\Put(
     *     path="/packages/{id}/intelligences/{intelligence}/completed",
     *     summary="تغییر وضعیت هوش پکیج به تکمیل شده",
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
     *         description="شناسه هوش",
     *         in="path",
     *         name="intelligence",
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
    public function intelligenceCompleted(Request $request, $package, $intelligence)
    {
        return $this->packageService->intelligenceCompleted($request, $package, $intelligence);
    }

    /**
     * Change status is completed package intelligence to false
     *
     * @OA\Put(
     *     path="/packages/{id}/intelligences/{intelligence}/uncompleted",
     *     summary="تغییر وضعیت هوش پکیج به تکمیل نشده",
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
     *         description="شناسه هوش",
     *         in="path",
     *         name="intelligence",
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
    public function intelligenceUncompleted(Request $request, $package, $intelligence)
    {
        return $this->packageService->intelligenceUncompleted($request, $package, $intelligence);
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
     *             mediaType="multipart/form-data",
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
}
