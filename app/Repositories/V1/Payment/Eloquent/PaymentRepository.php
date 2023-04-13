<?php

namespace App\Repositories\V1\Payment\Eloquent;

use App\Enums\Payment\PaymentStatus;
use App\Models\Payment;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Payment\Interfaces\PaymentRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{
    public function __construct(Payment $model)
    {
        parent::__construct($model);
    }

    /**
     * @return $this
     */
    public function statusPending(): static
    {
        $this->model->where('status', PaymentStatus::Pending);
        return $this;
    }

    public function findOrFailByInvoiceId($invoiceId): Model|Relation|Builder
    {
        return $this->model->where('invoice_id', $invoiceId)->firstOrFail();
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function filterUserPagination(Request $request): static
    {
        $this->model->when($request->filled('status') && in_array($request->status, PaymentStatus::asArray()), function ($q) use ($request) {
            $q->where('status', $request->status);
        });
        return $this;
    }
}
