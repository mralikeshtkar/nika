<?php

namespace App\Http\Controllers\V1\Api\Discount;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Discount\DiscountService;
use Illuminate\Http\Request;

class ApiDiscountController extends ApiBaseController
{
    /**
     * @var DiscountService
     */
    private DiscountService $discountService;

    /**
     * @param DiscountService $discountService
     */
    public function __construct(DiscountService $discountService)
    {
        $this->discountService = $discountService;
    }

    public function index(Request $request)
    {

    }

    public function store(Request $request)
    {

    }

    public function update(Request $request,$discounr)
    {

    }

    public function destroy(Request $request)
    {

    }
}
