<?php

namespace App\Repositories\V1\Payment\Eloquent;

use App\Models\Payment;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Payment\Interfaces\PaymentRepositoryInterface;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{
    public function __construct(Payment $model)
    {
        parent::__construct($model);
    }
}
