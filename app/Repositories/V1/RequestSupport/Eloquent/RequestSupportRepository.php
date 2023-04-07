<?php

namespace App\Repositories\V1\RequestSupport\Eloquent;

use App\Models\RequestSupport;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\RequestSupport\Interfaces\RequestSupportRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class RequestSupportRepository extends BaseRepository implements RequestSupportRepositoryInterface
{
    public function __construct(RequestSupport $model)
    {
        parent::__construct($model);
    }
}
