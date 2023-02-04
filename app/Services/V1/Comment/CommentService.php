<?php

namespace App\Services\V1\Comment;

use App\Repositories\V1\Comment\Interfaces\CommentRepositoryInterface;
use App\Responses\Api\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CommentService extends \App\Services\V1\BaseService
{
    /**
     * @var CommentRepositoryInterface
     */
    private CommentRepositoryInterface $commentRepository;

    #region Constructor

    /**
     * @param CommentRepositoryInterface $commentRepository
     */
    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    #endregion

    #region Public methods

    /**
     * @param Request $request
     * @param $comment
     * @return JsonResponse
     */
    public function update(Request $request, $comment): JsonResponse
    {
        $comment = $this->commentRepository->findOrFailById($comment);
        ApiResponse::authorize($request->user()->can('update', $comment));
        ApiResponse::validate($request->all(), [
            'body' => ['required', 'string'],
        ]);
        $this->commentRepository->update($comment, [
            'body' => $request->body,
        ]);
        return ApiResponse::message(trans("The :attribute was successfully updated", ['attribute' => trans('Comment')]), Response::HTTP_CREATED)
            ->send();
    }

    /**
     * @param Request $request
     * @param $comment
     * @return JsonResponse
     */
    public function destroy(Request $request, $comment): JsonResponse
    {
        $comment = $this->commentRepository->findOrFailById($comment);
        ApiResponse::authorize($request->user()->can('delete', $comment));
        $this->commentRepository->destroy($comment);
        return ApiResponse::message(trans("The :attribute was successfully deleted", ['attribute' => trans('Comment')]))->send();
    }

    #endregion

}
