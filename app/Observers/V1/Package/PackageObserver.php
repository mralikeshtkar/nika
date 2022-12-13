<?php

namespace App\Observers\V1\Package;

use App\Models\Package;
use App\Repositories\V1\Media\Interfaces\MediaRepositoryInterface;

class PackageObserver
{
    /**
     * @param Package $package
     * @return void
     */
    public function deleted(Package $package)
    {
        resolve(MediaRepositoryInterface::class)->destroy($package->video);
    }
}
