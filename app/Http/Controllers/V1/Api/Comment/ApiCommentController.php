<?php

namespace App\Http\Controllers\V1\Api\Comment;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Comment\CommentService;
use Illuminate\Http\Request;

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

    public function update(Request $request,$comment)
    {
        return $this->commentService->update($request,$comment);
    }

    public function destroy(Request $request,$comment)
    {
        return $this->commentService->destroy($request,$comment);
    }
}
