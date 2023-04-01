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

    public function index(Request $request, $rahjoo_support)
    {
        return $this->supportCommentService->index($request,$rahjoo_support);
    }

    public function store(Request $request, $rahjoo_support)
    {
        return $this->supportCommentService->store($request,$rahjoo_support);
    }
}
