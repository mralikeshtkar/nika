<?php

namespace App\Services\V1\Discount;

use App\Repositories\V1\Discount\Interfaces\DiscountRepositoryInterface;

class DiscountService extends \App\Services\V1\BaseService
{
    /**
     * @var DiscountRepositoryInterface
     */
    private DiscountRepositoryInterface $discountRepository;

    /**
     * @param DiscountRepositoryInterface $discountRepository
     */
    public function __construct(DiscountRepositoryInterface $discountRepository)
    {
        $this->discountRepository = $discountRepository;
    }
}
