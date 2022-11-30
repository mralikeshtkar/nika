<?php

namespace App\Http\Controllers\V1\Api\Skill;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Skill\SkillService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ApiSkillController extends ApiBaseController
{
    private SkillService $skillService;

    /**
     * @param SkillService $skillService
     */
    public function __construct(SkillService $skillService)
    {
        $this->skillService = $skillService;
    }

    /**
     * Get skills as pagination.
     *
     * @OA\Get (
     *     path="/skills",
     *     summary="لیست مهارت ها بصورت صفحه بندی",
     *     description="",
     *     tags={"مهارت"},
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
     *         description="جستجوی عنوان",
     *         in="query",
     *         name="title",
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
        return $this->skillService->index($request);
    }

    /**
     * Get all skills.
     *
     * @OA\Get(
     *     path="/skills/all",
     *     summary="لیست تمام مهارت ها",
     *     description="",
     *     tags={"مهارت"},
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function all(Request $request)
    {
        return $this->skillService->all($request);
    }

    /**
     * Store a skill.
     *
     * @OA\Post(
     *     path="/skills",
     *     summary="ثبت مهارت",
     *     description="",
     *     tags={"مهارت"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="عنوان"
     *                 ),
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
        return $this->skillService->store($request);
    }

    /**
     * Update a skill.
     *
     * @OA\Post(
     *     path="/skills/{id}",
     *     summary="بروزرسانی مهارت",
     *     description="",
     *     tags={"مهارت"},
     *     @OA\Parameter(
     *         description="شناسه مهارت",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"_method","title"},
     *                 @OA\Property(
     *                     property="_method",
     *                     type="string",
     *                     default="put",
     *                     enum={"put"},
     *                     description="این مقدار باید بصورت ثابت شود",
     *                 ),
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="عنوان"
     *                 ),
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
    public function update(Request $request, $skill)
    {
        return $this->skillService->update($request, $skill);
    }

    /**
     * Delete a skill.
     *
     * @OA\Delete(
     *     path="/skills/{id}",
     *     summary="حذف مهارت",
     *     description="",
     *     tags={"مهارت"},
     *     @OA\Parameter(
     *         description="شناسه مهارت",
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
    public function destroy(Request $request, $skill)
    {
        return $this->skillService->destroy($request, $skill);
    }
}
