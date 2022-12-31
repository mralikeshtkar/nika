<?php

namespace App\Http\Controllers\V1\Api\Role;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Role\RoleService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ApiRoleController extends ApiBaseController
{
    /**
     * @var RoleService
     */
    private RoleService $roleService;

    /**
     * ApiRoleController constructor.
     *
     * @param RoleService $roleService
     */
    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Get all roles with permissions as pagination.
     *
     * @OA\Get (
     *     path="/roles",
     *     summary="لیست نقش‌های کاربری همراه مجوزهایشان بصورت صفحه بندی",
     *     description="",
     *     tags={"نقش‌های کاربری"},
     *     @OA\Parameter(
     *         description="شماره صفحه",
     *         in="query",
     *         name="page",
     *         required=true,
     *         example=1,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Parameter(
     *         description="تعداد نمایش در هر صفحه",
     *         in="query",
     *         name="perPage",
     *         example=10,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Parameter(
     *         description="جستجوی نام انگلیسی و فارسی",
     *         in="query",
     *         name="name",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function index(Request $request)
    {
        return $this->roleService->index($request);
    }

    /**
     * Get all roles.
     *
     * @OA\Get (
     *     path="/roles/all",
     *     summary="لیست نقش‌های کاربری",
     *     description="",
     *     tags={"نقش‌های کاربری"},
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function all(Request $request)
    {
        return $this->roleService->all($request);
    }

    /**
     * Store a role.
     *
     * @OA\Post(
     *     path="/roles",
     *     summary="ثبت نقش کاربری",
     *     description="",
     *     tags={"نقش‌های کاربری"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"name","name_fa"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="نام انگلیسی"
     *                 ),
     *                 @OA\Property(
     *                     property="name_fa",
     *                     type="string",
     *                     description="نام فارسی"
     *                 ),
     *                  @OA\Property(
     *                     property="permissions[]",
     *                     type="array",
     *                     @OA\Items(type="string"),
     *                     description="مجوزها (نام مجوز)"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="ثبت با موفقیت انجام شد",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function store(Request $request)
    {
        return $this->roleService->store($request);
    }

    /**
     * Update a role.
     *
     * @OA\Post(
     *     path="/roles/{id}",
     *     summary="بروزرسانی نقش کاربری",
     *     description="نام انگلیسی و فارسی نقش کاربری هایی که قفل هستند، قابل تغییر نمیباشند",
     *     tags={"نقش‌های کاربری"},
     *     @OA\Parameter(
     *         description="شناسه نقش کاربری",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"_method"},
     *                 @OA\Property(
     *                     property="_method",
     *                     type="string",
     *                     default="put",
     *                     enum={"put"},
     *                     description="این مقدار باید بصورت ثابت شود",
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="نام انگلیسی"
     *                 ),
     *                 @OA\Property(
     *                     property="name_fa",
     *                     type="string",
     *                     description="نام فارسی"
     *                 ),
     *                  @OA\Property(
     *                     property="permissions[]",
     *                     type="array",
     *                     @OA\Items(type="string"),
     *                     description="مجوزها (نام مجوز)"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function update(Request $request, $role)
    {
        return $this->roleService->update($request, $role);
    }

    /**
     * Delete a role.
     *
     * @OA\Delete(
     *     path="/roles/{id}",
     *     summary="حذف نقش کاربری",
     *     description="نقش کاربری هایی که قفل هستند، قابل حذف نمیباشند",
     *     tags={"نقش‌های کاربری"},
     *     @OA\Parameter(
     *         description="شناسه نقش کاربری",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function destroy(Request $request, $role)
    {
        return $this->roleService->destroy($request, $role);
    }
}
