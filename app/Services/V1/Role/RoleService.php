<?php

namespace App\Services\V1\Role;

use App\Http\Resources\V1\PaginationResource;
use App\Http\Resources\V1\Role\RoleResource;
use App\Models\Permission;
use App\Models\Role;
use App\Repositories\V1\Role\Interfaces\RoleRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Rules\OnlyEnglishAndNumberRule;
use App\Services\V1\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class RoleService extends BaseService
{
    /**
     * @var RoleRepositoryInterface
     */
    private RoleRepositoryInterface $roleRepository;

    #region Constructor

    /**
     * RoleService constructor.
     *
     * @param RoleRepositoryInterface $roleRepository
     */
    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    #endregion

    #region Public methods

    /**
     * Get all roles with permissions as pagination.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('index', Role::class));
        $roles = $this->roleRepository->select(['id', 'name', 'name_fa', 'is_locked'])
            ->with(['permissions:id,name'])
            ->filterPagination($request)
            ->paginate($request->get('perPage', 10));
        $resource = PaginationResource::make($roles)->additional(['itemsResource' => RoleResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('roles', $resource)
            ->send();
    }

    /**
     * Get all roles.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function all(Request $request): JsonResponse
    {
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('roles', RoleResource::collection($this->roleRepository->select(['id', 'name', 'name_fa', 'is_locked'])->getAll()))
            ->send();
    }

    /**
     * Store a role.
     *
     * @param Request $request
     * @return JsonResponse|mixed
     */
    public function store(Request $request): mixed
    {
        ApiResponse::authorize($request->user()->can('create', Role::class));
        ApiResponse::validate($request->all(), [
            'name' => ['required', 'string', new OnlyEnglishAndNumberRule(), 'unique:' . Role::class . ',name'],
            'name_fa' => ['required', 'string',],
            'permissions' => ['nullable', 'array', 'exists:' . Permission::class . ',name'],
        ]);
        try {
            return DB::transaction(function () use ($request) {
                $role = $this->roleRepository->create([
                    'name' => $request->name,
                    'name_fa' => $request->name_fa,
                ]);
                $this->roleRepository->syncPermissions($role, $request->get('permissions', []));
                return ApiResponse::message(trans("The role was successfully registered"), Response::HTTP_CREATED)
                    ->addData('role', RoleResource::make($role->load('permissions:id,name')))
                    ->send();
            });
        } catch (Throwable $e) {
            return ApiResponse::error(trans("Internal server error"))->send();
        }
    }

    /**
     * Update a role.
     * If role is locked, Can change only permissions.
     *
     * @param Request $request
     * @param $role
     * @return JsonResponse|mixed
     */
    public function update(Request $request, $role): mixed
    {
        ApiResponse::authorize($request->user()->can('edit', Role::class));
        /** @var Role $role */
        $role = $this->roleRepository->findOrFailById($role);
        ApiResponse::validate($request->all(), collect([
            'permissions' => ['nullable', 'array', 'exists:' . Permission::class . ',name'],
        ])->when(!$role->isLocked(), function (Collection $collection) use ($role) {
            $collection->put('name', ['required', 'string', new OnlyEnglishAndNumberRule(), 'unique:' . Role::class . ',name,' . $role->id])
                ->put('name_fa', ['required', 'string',]);
        })->toArray());
        try {
            return DB::transaction(function () use ($request, $role) {
                if (!$role->isLocked())
                    $this->roleRepository->update($role, [
                        'name' => $request->name,
                        'name_fa' => $request->name_fa,
                    ]);
                $this->roleRepository->syncPermissions($role, $request->get('permissions', []));
                return ApiResponse::message(trans("The :attribute was successfully updated", ['attribute' => trans('Role')]))
                    ->addData('role', RoleResource::make($role->load('permissions:id,name')))
                    ->send();
            });
        } catch (Throwable $e) {
            return ApiResponse::error(trans("Internal server error"))->send();
        }
    }

    /**
     * Destroy a role.
     *
     * @param $request
     * @param $role
     * @return JsonResponse
     */
    public function destroy($request, $role): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('delete', Role::class));
        /** @var Role $role */
        $role = $this->roleRepository->findOrFailById($role);
        if ($role->isLocked()) return ApiResponse::error(trans("The role is locked"), Response::HTTP_BAD_REQUEST)->send();
        $this->roleRepository->destroy($role);
        return ApiResponse::message(trans("The :attribute was successfully deleted", ['attribute' => trans('Role')]))->send();
    }

    #endregion
}
