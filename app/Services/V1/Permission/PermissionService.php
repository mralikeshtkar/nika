<?php

namespace App\Services\V1\Permission;

use App\Http\Resources\V1\Permission\PermissionResource;
use App\Models\Permission;
use App\Repositories\V1\Permission\Interfaces\PermissionRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionService extends BaseService
{
    /**
     * @var PermissionRepositoryInterface
     */
    private PermissionRepositoryInterface $permissionRepository;

    #region Constructor

    /**
     * PermissionService constructor.
     *
     * @param PermissionRepositoryInterface $permissionRepository
     */
    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    #endregion

    #region Public methods

    /**
     * Get all permissions.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('index', Permission::class));
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('permissions', PermissionResource::collection($this->permissionRepository->getAll()))
            ->send();
    }

    #endregion

}
