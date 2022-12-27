<?php

namespace App\Http\Controllers\V1\Api\DocumentGroup;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\DocumentGroup\DocumentGroupService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ApiDocumentGroupController extends ApiBaseController
{
    /**
     * @var DocumentGroupService
     */
    private DocumentGroupService $documentGroupService;

    /**
     * @param DocumentGroupService $documentGroupService
     */
    public function __construct(DocumentGroupService $documentGroupService)
    {
        $this->documentGroupService = $documentGroupService;
    }

    /**
     * Get document groups as pagination.
     *
     * @OA\Get (
     *     path="/document-groups",
     *     summary="لیست گروه های مستندات بصورت صفحه بندی",
     *     description="",
     *     tags={"گروه مستندات"},
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
        return $this->documentGroupService->index($request);
    }

    /**
     * Store a document group.
     *
     * @OA\Post(
     *     path="/document-groups",
     *     summary="ثبت گروه مستندات",
     *     description="",
     *     tags={"گروه مستندات"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title","format"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="عنوان"
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="number",
     *                     description="توضیحات"
     *                 ),
     *                 @OA\Property(
     *                     property="format",
     *                     type="number",
     *                     description="فرمت فایل"
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
        return $this->documentGroupService->store($request);
    }

    /**
     * Update a document group.
     *
     * @OA\Post(
     *     path="/document-groups/{id}",
     *     summary="بروزرسانی گروه مستندات",
     *     description="",
     *     tags={"گروه مستندات"},
     *     @OA\Parameter(
     *         description="شناسه گروه مستندات",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"_method","title","format"},
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
     *                     property="description",
     *                     type="number",
     *                     description="توضیحات"
     *                 ),
     *                 @OA\Property(
     *                     property="format",
     *                     type="number",
     *                     description="فرمت فایل"
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
    public function update(Request $request, $documentGroup)
    {
        return $this->documentGroupService->update($request, $documentGroup);
    }

    /**
     * Delete a document group.
     *
     * @OA\Delete(
     *     path="/document-groups/{id}",
     *     summary="حذف گروه مستندات",
     *     description="",
     *     tags={"گروه مستندات"},
     *     @OA\Parameter(
     *         description="شناسه گروه مستندات",
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
    public function destroy(Request $request, $documentGroup)
    {
        return $this->documentGroupService->destroy($request, $documentGroup);
    }
}
