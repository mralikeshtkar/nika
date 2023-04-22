<?php

namespace App\Repositories\V1\Product\Eloquent;

use App\Models\Product;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Product\Interfaces\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    /**
     * @return $this
     */
    public function withPackageTitle(): static
    {
        $this->model->withAggregate('package','title');
        return $this;
    }
}
