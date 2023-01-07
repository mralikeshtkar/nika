<?php

namespace App\Http\Controllers\V1\Api\Package;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Package\IntelligencePackageService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ApiIntelligencePackageController extends ApiBaseController
{
    /**
     * @var IntelligencePackageService
     */
    private IntelligencePackageService $intelligencePackageService;

    /**
     * @param IntelligencePackageService $intelligencePackageService
     */
    public function __construct(IntelligencePackageService $intelligencePackageService)
    {
        $this->intelligencePackageService = $intelligencePackageService;
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
    public function index(Request $request, $package)
    {
        return $this->intelligencePackageService->index($request, $package);
    }

    /**
     * Show a package intelligence
     *
     * @OA\Get (
     *     path="/intelligence-packages/{id}",
     *     summary="دریافت یک هوش پکیج",
     *     description="",
     *     tags={"پکیج"},
     *     @OA\Parameter(
     *         description="شناسه جدول میانی",
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
    public function show(Request $request, $intelligencePackage)
    {
        return $this->intelligencePackageService->show($request, $intelligencePackage);
    }

    /**
     * @OA\Get (
     *     path="/intelligence-packages/{id}/points",
     *     summary="دریافت ارزش های یک هوش پکیج بصورت صفحه بندی",
     *     description="",
     *     tags={"پکیج"},
     *     @OA\Parameter(
     *         description="شناسه جدول میانی",
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
    public function points(Request $request, $intelligencePackage)
    {
        return $this->intelligencePackageService->points($request, $intelligencePackage);
    }

    /**
     * @OA\Get (
     *     path="/intelligence-packages/{id}/feedbacks",
     *     summary="دریافت بازخورد های یک هوش پکیج بصورت صفحه بندی",
     *     description="",
     *     tags={"پکیج"},
     *     @OA\Parameter(
     *         description="شناسه جدول میانی",
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
    public function feedbacks(Request $request, $intelligencePackage)
    {
        return $this->intelligencePackageService->feedbacks($request, $intelligencePackage);
    }

    /**
     * Store a package intelligence.
     *
     * @OA\Post(
     *     path="/packages/{id}/intelligences",
     *     summary="ثبت هوش برای پکیج",
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
     *                 required={"intelligence_id"},
     *                 @OA\Property(
     *                     property="intelligence_id",
     *                     type="number",
     *                     description="شناسه هوش"
     *                 )
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
    public function store(Request $request, $package)
    {
        return $this->intelligencePackageService->store($request, $package);
    }

    /**
     * Change status is completed package intelligence to true
     *
     * @OA\Put(
     *     path="/intelligence-packages/{id}/completed",
     *     summary="تغییر وضعیت هوش پکیج به تکمیل شده",
     *     description="",
     *     tags={"پکیج"},
     *     @OA\Parameter(
     *         description="شناسه جدول میانی",
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
    public function completed(Request $request, $intelligencePackage)
    {
        return $this->intelligencePackageService->completed($request, $intelligencePackage);
    }

    /**
     * Change status is completed package intelligence to false
     *
     * @OA\Put(
     *     path="/intelligence-packages/{id}/uncompleted",
     *     summary="تغییر وضعیت هوش پکیج به تکمیل نشده",
     *     description="",
     *     tags={"پکیج"},
     *     @OA\Parameter(
     *         description="شناسه جدول میانی",
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
    public function uncompleted(Request $request, $intelligencePackage)
    {
        return $this->intelligencePackageService->uncompleted($request, $intelligencePackage);
    }

    /**
     * Destroy package intelligence
     *
     * @OA\Delete(
     *     path="/intelligence-packages/{id}",
     *     summary="حذف هوش پکیج",
     *     description="",
     *     tags={"پکیج"},
     *     @OA\Parameter(
     *         description="شناسه جدول میانی",
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
    public function destroy(Request $request, $intelligencePackage)
    {
        return $this->intelligencePackageService->destroy($request, $intelligencePackage);
    }
}
