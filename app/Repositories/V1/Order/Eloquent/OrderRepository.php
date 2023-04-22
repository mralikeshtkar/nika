<?php

namespace App\Repositories\V1\Order\Eloquent;

use App\Enums\Order\OrderStatus;
use App\Models\Media;
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
        $this->model->when($request->filled('status') && in_array($request->status, OrderStatus::asArray()), function ($q) use ($request) {
            $q->where('status', $request->status);
        });
        return $this;
    }

    public function uploadReceipt($order, $file)
    {
        if ($order->receipt) $order->receipt->delete();
        return $order->setDisk(Media::MEDIA_PUBLIC_DISK)
            ->setDirectory(Order::MEDIA_DIRECTORY_RECEIPTS)
            ->setCollection(Order::MEDIA_COLLECTION_RECEIPT)
            ->addMedia($file);
    }
}
