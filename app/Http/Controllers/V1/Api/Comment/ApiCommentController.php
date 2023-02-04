<?php

namespace App\Http\Controllers\V1\Api\Comment;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Comment\CommentService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ApiCommentController extends ApiBaseController
{
    /**
     * @var CommentService
     */
    private CommentService $commentService;

    /**
     * @param CommentService $commentService
     */
    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * @OA\Post(
     *     path="/comments/{id}",
     *     summary="بروزرسانی کامنت",
     *     description="",
     *     tags={"کامنت"},
     *     @OA\Parameter(
     *         description="شناسه کامنت",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"_method","body"},
     *                 @OA\Property(
     *                     property="_method",
     *                     type="string",
     *                     default="put",
     *                     enum={"put"},
     *                     description="این مقدار باید بصورت ثابت شود",
     *                 ),
     *                 @OA\Property(
     *                     property="body",
     *                     type="string",
     *                     description="عنوان"
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
    public function update(Request $request,$comment)
    {
        return $this->commentService->update($request,$comment);
    }

    /**
     * @OA\Delete(
     *     path="/comments/{id}",
     *     summary="حذف کامنت",
     *     description="",
     *     tags={"کامنت"},
     *     @OA\Parameter(
     *         description="شناسه کامنت",
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
    public function destroy(Request $request,$comment)
    {
        return $this->commentService->destroy($request,$comment);
    }
}
