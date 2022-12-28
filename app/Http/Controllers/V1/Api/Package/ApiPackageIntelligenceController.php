<?php

namespace App\Http\Controllers\V1\Api\Package;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Package\PackageIntelligenceService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ApiPackageIntelligenceController extends ApiBaseController
{
    /**
     * @var PackageIntelligenceService
     */
    private PackageIntelligenceService $packageIntelligenceService;

    /**
     * @param PackageIntelligenceService $packageIntelligenceService
     */
    public function __construct(PackageIntelligenceService $packageIntelligenceService)
    {
        $this->packageIntelligenceService = $packageIntelligenceService;
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
        return $this->packageIntelligenceService->index($request, $package);
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
        return $this->packageIntelligenceService->store($request, $package);
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
    public function completed(Request $request, $package, $intelligence)
    {
        return $this->packageIntelligenceService->completed($request, $package, $intelligence);
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
    public function uncompleted(Request $request, $package, $intelligence)
    {
        return $this->packageIntelligenceService->uncompleted($request, $package, $intelligence);
    }
}
