<?php

namespace App\Http\Controllers\V1\Api\IntelligenceFeedback;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\IntelligenceFeedback\IntelligenceFeedbackService;

class ApiIntelligenceFeedbackController extends ApiBaseController
{
    /**
     * @var IntelligenceFeedbackService
     */
    private IntelligenceFeedbackService $intelligenceFeedbackService;

    /**
     * @param IntelligenceFeedbackService $intelligenceFeedbackService
     */
    public function __construct(IntelligenceFeedbackService $intelligenceFeedbackService)
    {
        $this->intelligenceFeedbackService = $intelligenceFeedbackService;
    }
}
