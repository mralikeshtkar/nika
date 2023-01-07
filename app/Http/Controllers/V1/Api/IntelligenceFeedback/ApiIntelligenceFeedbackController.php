<?php

namespace App\Http\Controllers\V1\Api\IntelligenceFeedback;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\IntelligenceFeedback\IntelligenceFeedbackService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ApiIntelligenceFeedbackController extends ApiBaseController
{
    /**
     * @var IntelligenceFeedbackService
     */
    private IntelligenceFeedbackService $intelligenceFeedbackService;

    /**
     * @param IntelligenceFeedbackService $intelligenceFeedbackService
     */
    public function __construct(IntelligenceFeedbackService $intelligenceFeedbackService)
    {
        $this->intelligenceFeedbackService = $intelligenceFeedbackService;
    }

    /**
     * Get intelligence feedbacks as pagination.
     *
     * @OA\Get (
     *     path="/intelligence-feedbacks",
     *     summary="لیست بازخوردهای هوش بصورت صفحه بندی",
     *     description="",
     *     tags={"بازخورد هوش"},
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
     *         description="عنوان",
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
        return $this->intelligenceFeedbackService->index($request);
    }

    /**
     * Store a intelligence feedback.
     *
     * @OA\Post(
     *     path="/intelligence-feedbacks",
     *     summary="ثبت بازخورد هوش",
     *     description="",
     *     tags={"بازخورد هوش"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"intelligence_package_id","title","max_point"},
     *                 @OA\Property(
     *                     property="intelligence_package_id",
     *                     type="number",
     *                     description="شناسه جدول میانی هوش پکیج"
     *                 ),
     *                 @OA\Property(
     *                     property="title",
     *                     type="number",
     *                     description="عنوان"
     *                 ),
     *                 @OA\Property(
     *                     property="max_point",
     *                     type="number",
     *                     description="حداکثر نمره"
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
        return $this->intelligenceFeedbackService->store($request);
    }

    /**
     * Store multiple intelligence feedback.
     *
     * @OA\Post(
     *     path="/intelligence-feedbacks/multiple",
     *     summary="ثبت چندتایی بازخورد هوش",
     *     description="",
     *     tags={"بازخورد هوش"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"intelligence_package_id"},
     *                 @OA\Property(
     *                     property="intelligence_package_id",
     *                     type="number",
     *                     description="شناسه جدول میانی هوش پکیج"
     *                 ),
     *                 @OA\Property(
     *                     property="points",
     *                     type="array",
     *                     description="ارزش ها",
     *                     @OA\Items(
     *                        type="object",
     *                        @OA\Property(property="title", type="string"),
     *                        @OA\Property(property="max_point", type="number")
     *                     )
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
    public function storeMultiple(Request $request)
    {
        return $this->intelligenceFeedbackService->storeMultiple($request);
    }

    /**
     * update intelligence feedback.
     *
     * @OA\Post(
     *     path="/intelligence-feedbacks/{id}",
     *     summary="بروزرسانی بازخورد هوش",
     *     description="",
     *     tags={"بازخورد هوش"},
     *     @OA\Parameter(
     *         description="شناسه بازخورد هوش",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"_method","title","max_point"},
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
     *                     property="max_point",
     *                     type="string",
     *                     description="امتیاز"
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
    public function update(Request $request, $intelligenceFeedback)
    {
        return $this->intelligenceFeedbackService->update($request, $intelligenceFeedback);
    }

    /**
     * Delete a intelligence feedback.
     *
     * @OA\Delete(
     *     path="/intelligence-feedbacks/{id}",
     *     summary="حذف بازخورد هوش",
     *     description="",
     *     tags={"بازخورد هوش"},
     *     @OA\Parameter(
     *         description="شناسه بازخورد هوش",
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
    public function destroy(Request $request, $intelligenceFeedback)
    {
        return $this->intelligenceFeedbackService->destroy($request,$intelligenceFeedback);
    }
}
