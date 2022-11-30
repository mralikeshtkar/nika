<?php

namespace App\Http\Controllers\V1\Api\Permission;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Permission\PermissionService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ApiPermissionController extends ApiBaseController
{
    /**
     * @var PermissionService
     */
    private PermissionService $permissionService;

    /**
     * @param PermissionService $permissionService
     */
    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * Get all permissions.
     *
     * @OA\Get (
     *     path="/permissions",
     *     summary="لیست مجوزها",
     *     description="",
     *     tags={"مجوزها"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="شما مجوز لازم برای اجرای این مسیر رو ندارید",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="حساب کاربر مسدود میباشد",
     *         @OA\JsonContent()
     *     )
     * )
     */
    public function index(Request $request)
    {
        return $this->permissionService->index($request);
    }
}
