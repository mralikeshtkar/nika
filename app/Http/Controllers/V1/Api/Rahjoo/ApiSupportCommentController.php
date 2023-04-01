<?php

namespace App\Http\Controllers\V1\Api\Rahjoo;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\SupportComment\SupportCommentService;
use Illuminate\Http\Request;

class ApiSupportCommentController extends ApiBaseController
{
    /**
     * @var SupportCommentService
     */
    private SupportCommentService $supportCommentService;

    /**
     * @param SupportCommentService $supportCommentService
     */
    public function __construct(SupportCommentService $supportCommentService)
    {
        $this->supportCommentService = $supportCommentService;
    }

    /**
     * @OA\Get(
     *     path="/support/{support}/comments",
     *     summary="دریافت دیدگاه های پشتیبانی",
     *     description="",
     *     tags={"پشتیبان"},
     *     @OA\Parameter(
     *         description="شناسه پشتیبانی",
     *         in="path",
     *         name="support",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent()
     *     )
     * )
     */
    public function index(Request $request, $rahjoo_support)
    {
        return $this->supportCommentService->index($request,$rahjoo_support);
    }

    /**
     * @OA\Post (
     *     path="/support/{support}/comments",
     *     summary="ثبت دیدگاه پشتیبانی",
     *     description="",
     *     tags={"پشتیبان"},
     *     @OA\Parameter(
     *         description="شناسه پشتیبانی",
     *         in="path",
     *         name="support",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"text"},
     *                 @OA\Property(
     *                     property="text",
     *                     type="string",
     *                     description="متن"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent()
     *     )
     * )
     */
    public function store(Request $request, $rahjoo_support)
    {
        return $this->supportCommentService->store($request,$rahjoo_support);
    }
}
