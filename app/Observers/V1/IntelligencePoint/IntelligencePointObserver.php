<?php

namespace App\Observers\V1\IntelligencePoint;

use App\Models\IntelligencePoint;
use App\Repositories\V1\Package\Interfaces\IntelligencePackageRepositoryInterface;

class IntelligencePointObserver
{
    /**
     * @param IntelligencePoint $intelligencePoint
     * @return void
     */
    public function created(IntelligencePoint $intelligencePoint)
    {
        resolve(IntelligencePackageRepositoryInterface::class)->touch($intelligencePoint->packageIntelligence());
    }

    /**
     * @param IntelligencePoint $intelligencePoint
     * @return void
     */
    public function updated(IntelligencePoint $intelligencePoint)
    {
        resolve(IntelligencePackageRepositoryInterface::class)->touch($intelligencePoint->packageIntelligence());
    }
}
