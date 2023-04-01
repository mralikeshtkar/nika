<?php

namespace App\Services\V1\SupportComment;

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
        /** @var RahjooSupport $rahjoo_support */
        $rahjoo_support = resolve(RahjooSupportRepositoryInterface::class)->findOrFailById($rahjoo_support);
        ApiResponse::authorize($request->user()->can('indexComment', $rahjoo_support));
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('cities', $rahjoo_support->comments()->latest()->get())
            ->send();
    }

    public function store(Request $request, $rahjoo_support): JsonResponse
    {
        $rahjoo_support = resolve(RahjooSupportRepositoryInterface::class)->findOrFailById($rahjoo_support);
        ApiResponse::authorize($request->user()->can('storeComment', $rahjoo_support));
        ApiResponse::validate($request->all(), [
            'text' => ['required', 'string'],
        ]);
        $this->supportCommentRepository->create([
            'user_id' => $request->user()->id,
            'rahjoo_support_id' => $rahjoo_support->id,
            'step' => $rahjoo_support->step,
            'body' => $request->body,
        ]);
        return ApiResponse::message(trans("The :attribute was successfully registered", ['attribute' => trans('SupportComment')]), Response::HTTP_CREATED)
            ->send();
    }
}
