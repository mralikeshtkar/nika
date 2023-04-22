<?php

namespace App\Repositories\V1\Discount\Eloquent;

use App\Models\Discount;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Discount\Interfaces\DiscountRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class DiscountRepository extends BaseRepository implements DiscountRepositoryInterface
{
    public function __construct(Discount $model)
    {
        parent::__construct($model);
    }
}
