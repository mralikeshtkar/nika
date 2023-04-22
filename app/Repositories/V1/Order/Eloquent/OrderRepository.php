<?php

namespace App\Repositories\V1\Order\Eloquent;

use App\Enums\Order\OrderStatus;
use App\Models\Order;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Order\Interfaces\OrderRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function filterPagination(Request $request): static
    {
        dd(OrderStatus::asArray());
        $this->model->when($request->filled('status'), function ($q) use ($request) {

        });
        return $this;
    }
}
