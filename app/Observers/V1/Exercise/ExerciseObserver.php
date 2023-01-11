<?php

namespace App\Observers\V1\Exercise;

use App\Models\Exercise;
use App\Repositories\V1\Package\Interfaces\IntelligencePackageRepositoryInterface;

class ExerciseObserver
{
    /**
     * @param Exercise $exercise
     * @return void
     */
    public function created(Exercise $exercise)
    {
        resolve(IntelligencePackageRepositoryInterface::class)->touch($exercise->intelligencePackage());
    }

    /**
     * @param Exercise $exercise
     * @return void
     */
    public function updated(Exercise $exercise)
    {
        resolve(IntelligencePackageRepositoryInterface::class)->touch($exercise->intelligencePackage());
    }
}
