<?php

namespace App\Http\Controllers\V1\Api\Rahjoo;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Rahjoo\RahjooService;
use App\Services\V1\Rahjoo\RahjooSupportService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ApiRahjooSupportController extends ApiBaseController
{
    private RahjooSupportService $rahjooSupportService;

}
