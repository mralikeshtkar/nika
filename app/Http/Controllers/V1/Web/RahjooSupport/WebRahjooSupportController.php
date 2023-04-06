<?php

namespace App\Http\Controllers\V1\Web\RahjooSupport;

use App\Http\Controllers\V1\Web\WebBaseController;
use App\Services\V1\Rahjoo\RahjooSupportService;
use Illuminate\Http\Request;

class WebRahjooSupportController extends WebBaseController
{
    /**
     * @var RahjooSupportService
     */
    private RahjooSupportService $rahjooSupportService;

    /**
     * @param RahjooSupportService $rahjooSupportService
     */
    public function __construct(RahjooSupportService $rahjooSupportService)
    {
        $this->rahjooSupportService = $rahjooSupportService;
    }

    public function verifyPayment(Request $request, $rahjooSupport)
    {
        return $this->rahjooSupportService->verifyPayment($request, $rahjooSupport);
    }
}
