<?php

namespace App\Observers\V1\IntelligenceFeedback;

use App\Models\IntelligenceFeedback;
use App\Repositories\V1\Package\Interfaces\IntelligencePackageRepositoryInterface;

class IntelligenceFeedbackObserver
{
    /**
     * @param IntelligenceFeedback $intelligenceFeedback
     * @return void
     */
    public function created(IntelligenceFeedback $intelligenceFeedback)
    {
        resolve(IntelligencePackageRepositoryInterface::class)->touch($intelligenceFeedback->intelligencePackage());
    }

    /**
     * @param IntelligenceFeedback $intelligenceFeedback
     * @return void
     */
    public function updated(IntelligenceFeedback $intelligenceFeedback)
    {
        resolve(IntelligencePackageRepositoryInterface::class)->touch($intelligenceFeedback->intelligencePackage());
    }
}
