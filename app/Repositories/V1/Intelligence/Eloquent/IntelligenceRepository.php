<?php

namespace App\Repositories\V1\Intelligence\Eloquent;

use App\Models\Intelligence;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Intelligence\Interfaces\IntelligenceRepositoryInterface;

class IntelligenceRepository extends BaseRepository implements IntelligenceRepositoryInterface
{
    public function __construct(Intelligence $model)
    {
        parent::__construct($model);
    }
}
