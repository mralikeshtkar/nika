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

    public function store(Request $request, $rahjooSupport)
    {
        return $this->supportCommentService->store($request,$rahjooSupport);
    }
}
