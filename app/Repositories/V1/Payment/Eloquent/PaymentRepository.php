<?php

namespace App\Repositories\V1\Payment\Eloquent;

use App\Enums\Payment\PaymentStatus;
use App\Models\Payment;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Payment\Interfaces\PaymentRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

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
}
