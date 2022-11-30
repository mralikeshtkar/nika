<?php

namespace App\Http\Controllers\V1\Api\Intelligence;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Intelligence\IntelligenceService;

class ApiIntelligenceController extends ApiBaseController
{
    /**
     * @var IntelligenceService
     */
    private IntelligenceService $intelligenceService;

    /**
     * @param IntelligenceService $intelligenceService
     */
    public function __construct(IntelligenceService $intelligenceService)
    {
        $this->intelligenceService = $intelligenceService;
    }
}
