<?php

namespace App\Http\Controllers\V1\Api\Media;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Media\MediaService;
use Illuminate\Http\Request;

class ApiMediaController extends ApiBaseController
{
    /**
     * @var MediaService
     */
    private MediaService $mediaService;

    /**
     * @param MediaService $mediaService
     */
    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public function download(Request $request,$media,$file = "original")
    {
        return $this->mediaService->download($request,$media,$file);
    }
}
