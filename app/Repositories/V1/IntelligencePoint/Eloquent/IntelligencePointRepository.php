<?php

namespace App\Repositories\V1\IntelligencePoint\Eloquent;

use App\Models\IntelligencePoint;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\IntelligencePoint\Interfaces\IntelligencePointRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class IntelligencePointRepository extends BaseRepository implements IntelligencePointRepositoryInterface
{
    public function __construct(IntelligencePoint $model)
    {
        parent::__construct($model);
    }
}
