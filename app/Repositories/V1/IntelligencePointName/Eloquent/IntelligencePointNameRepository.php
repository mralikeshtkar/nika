<?php

namespace App\Repositories\V1\IntelligencePointName\Eloquent;

use App\Models\IntelligencePointName;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\IntelligencePointName\Interfaces\IntelligencePointNameRepositoryInterface;

class IntelligencePointNameRepository extends BaseRepository implements IntelligencePointNameRepositoryInterface
{
    public function __construct(IntelligencePointName $model)
    {
        parent::__construct($model);
    }

    public function update($model, array $attributes)
    {
        parent::update($model, $attributes);
        return $model->update();
    }

}
