<?php

namespace App\Http\Controllers\V1\Api\Rahjoo;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Rahjoo\RahjooService;
use App\Services\V1\Rahjoo\RahjooSupportService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ApiRahjooSupportController extends ApiBaseController
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

    public function show(Request $request, $rahjooSupport)
    {
        return $this->rahjooSupportService->show($request,$rahjooSupport);
    }

}
