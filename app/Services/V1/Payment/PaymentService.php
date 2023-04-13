<?php

namespace App\Services\V1\Payment;

use App\Repositories\V1\Payment\Eloquent\PaymentRepository;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;

class PaymentService
{
    /**
     * @var PaymentRepository
     */
    private PaymentRepository $paymentRepository;

    /**
     * @param PaymentRepository $paymentRepository
     */
    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

}
