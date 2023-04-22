<?php

namespace App\Repositories\V1\Discount\Eloquent;

use App\Models\Discount;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Discount\Interfaces\DiscountRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class DiscountRepository extends BaseRepository implements DiscountRepositoryInterface
{
    public function __construct(Discount $model)
    {
        parent::__construct($model);
    }

    public function findByCode($code)
    {
        return $this->model->active()
            ->where(function (Builder $builder) {
                $builder->whereNull('enable_at')
                    ->orWhere('enable_at', '>=', now());
            })->where(function (Builder $builder) {
                $builder->whereNull('expire_at')
                    ->orWhere('expire_at', '<=', now());
            })->where(function (Builder $builder) {
                $builder->whereNull('usage_limitation')
                    ->orWhere('usage_limitation', '>', 0);
            })->where('code', $code)
            ->first();
    }
}
