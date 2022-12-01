<?php

namespace App\Http\Controllers\V1\Api;

use App\Http\Controllers\Controller;
use OpenApi\Annotations as OA;

/**
 * @OA\Server(url="https://rashtdev.ir/api/v1")
 * @OA\Server(url="http://127.0.0.1:8000/api/v1")
 * @OA\Info(
 *     version="1.0",
 *     title="Nika Web Services"
 * )
 */
class ApiBaseController extends Controller
{

}
