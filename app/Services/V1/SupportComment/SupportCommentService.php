<?php

namespace App\Services\V1\SupportComment;

use App\Http\Resources\V1\SupportComment\SupportCommentResource;
use App\Models\Permission;
use App\Models\RahjooSupport;
use App\Repositories\V1\Rahjoo\Interfaces\RahjooSupportRepositoryInterface;
use App\Repositories\V1\SupportComment\Interfaces\SupportCommentRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SupportCommentService extends BaseService
{
    /**
     * @var SupportCommentRepositoryInterface
     */
    private SupportCommentRepositoryInterface $supportCommentRepository;

    /**
     * @param SupportCommentRepositoryInterface $supportCommentRepository
     */
    public function __construct(SupportCommentRepositoryInterface $supportCommentRepository)
    {
        $this->supportCommentRepository = $supportCommentRepository;
    }

    /**
     * @param Request $request
     * @param $rahjoo_support
     * @return JsonResponse
     */
    public function index(Request $request, $rahjoo_support): JsonResponse
    {
        dd($request->all());
        /** @var RahjooSupport $rahjoo_support */
        $rahjoo_support = resolve(RahjooSupportRepositoryInterface::class)->findOrFailById($rahjoo_support);
        ApiResponse::authorize($request->user()->can('indexComment', $rahjoo_support));
        $comments = $rahjoo_support->comments()
            ->select(['id','user_id','rahjoo_support_id','body','step','created_at'])
            ->with(['user:id,first_name,last_name'])
            ->latest()
            ->get();
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('comments', SupportCommentResource::collection($comments))
            ->send();
    }

    public function store(Request $request, $rahjoo_support): JsonResponse
    {
        $rahjoo_support = resolve(RahjooSupportRepositoryInterface::class)->findOrFailById($rahjoo_support);
        ApiResponse::authorize($request->user()->can('storeComment', $rahjoo_support));
        ApiResponse::validate($request->all(), [
            'body' => ['required', 'string'],
        ]);
        $comment = $this->supportCommentRepository->create([
            'user_id' => $request->user()->id,
            'rahjoo_support_id' => $rahjoo_support->id,
            'step' => $rahjoo_support->step,
            'body' => $request->body,
        ]);
        return ApiResponse::message(trans("The :attribute was successfully registered", ['attribute' => trans('SupportComment')]), Response::HTTP_CREATED)
            ->addData('comment',new SupportCommentResource($comment))
            ->send();
    }
}
