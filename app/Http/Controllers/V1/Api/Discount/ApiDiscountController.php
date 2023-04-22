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
        return $this->discountService->index($request);
    }

    public function store(Request $request)
    {
        return $this->discountService->store($request);
    }

    public function update(Request $request,$discount)
    {
        return $this->discountService->update($request,$discount);
    }

    public function destroy(Request $request,$discount)
    {
        return $this->discountService->destroy($request,$discount);
    }
}
