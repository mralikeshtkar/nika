<?php

namespace App\Repositories\V1\Product\Eloquent;

use App\Enums\Product\ProductStatus;
use App\Models\Product;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Product\Interfaces\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

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
        $this->model->withAggregate('package', 'title');
        return $this;
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function filterStatus(Request $request): static
    {
        $this->model->when($request->filled('status') && in_array($request->status, ProductStatus::asArray()), function ($q) use ($request) {
            $q->where('status',$request->status);
        });
        return $this;
    }
}
