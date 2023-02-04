<?php

namespace App\Http\Controllers\V1\Api;

use App\Http\Controllers\Controller;
use App\Responses\Api\ApiResponse;
use App\Rules\MobileRule;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/*https://api.rashtdev.ir/api/v1*/

/**
 * @OA\Info(
 *     version="1.0",
 *     title="Nika Web Services"
 * )
 */
class ApiBaseController extends Controller
{
    /**
     * @OA\Post(
     *     path="/token",
     *     summary="",
     *     description="",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"mobile"},
     *                 @OA\Property(
     *                     property="mobile",
     *                     type="string",
     *                     example="+989123456789",
     *                     description="شماره موبایل"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent()
     *     )
     * )
     */
    public function token(Request $request)
    {
        ApiResponse::validate($request->all(), [
            'mobile' => ['nullable', new MobileRule()],
        ]);
        $mobile = $request->filled('mobile') ? to_valid_mobile_number($request->mobile) : "+989123456789";
        $user = \App\Models\User::query()->where('mobile', $mobile)->firstOrFail();
        return "Bearer " . $user->generateToken();
    }
}
