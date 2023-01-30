<?php

namespace App\Http\Controllers\V1\Api\Rahjoo;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Rahjoo\RahjooService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ApiRahjooController extends ApiBaseController
{
    private RahjooService $rahjooService;

    /**
     * @param RahjooService $rahjooService
     */
    public function __construct(RahjooService $rahjooService)
    {
        $this->rahjooService = $rahjooService;
    }

    /**
     * Get cities as pagination.
     *
     * @OA\Get (
     *     path="/rahjoos",
     *     summary="لیست رهجو ها بهمراه کاربر بصورت صفحه بندی",
     *     description="",
     *     tags={"رهجو"},
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
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function index(Request $request)
    {
        return $this->rahjooService->index($request);
    }

    /**
     * Show a rahjoo.
     *
     * @OA\Get(
     *     path="/rahjoos/{rahjoo_id}",
     *     summary="نمایش اطلاعات رهجو",
     *     description="",
     *     tags={"رهجو"},
     *     @OA\Parameter(
     *         description="شناسه رهجو",
     *         in="path",
     *         name="rahjoo_id",
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
    public function show(Request $request, $rahjoo)
    {
        return $this->rahjooService->show($request, $rahjoo);
    }

    /**
     * Update or store a rahjoo.
     *
     * @OA\Post(
     *     path="/rahjoos/{user_id}",
     *     summary="ثبت یا بروزرسانی رهجو",
     *     description="",
     *     tags={"رهجو"},
     *     @OA\Parameter(
     *         description="شناسه کاربر",
     *         in="path",
     *         name="user_id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="agent_id",
     *                     type="number",
     *                     description="شناسه کاربر نماینده"
     *                 ),
     *                 @OA\Property(
     *                     property="school",
     *                     type="number",
     *                     description="نام مدرسه"
     *                 ),
     *                 @OA\Property(
     *                     property="which_child_of_family",
     *                     type="number",
     *                     description="فرزند چندم خانواده؟ حداقل 1"
     *                 ),
     *                 @OA\Property(
     *                     property="disease_background",
     *                     type="string",
     *                     description="سابقه های بیماری"
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
    public function store(Request $request, $user)
    {
        return $this->rahjooService->store($request, $user);
    }

    /**
     * @OA\Post(
     *     path="/rahjoos/{id}/assign-package",
     *     summary="ثبت پکیج برای رهجو",
     *     description="",
     *     tags={"رهجو"},
     *     @OA\Parameter(
     *         description="شناسه رهجو",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"_method","package_id"},
     *                 @OA\Property(
     *                     property="_method",
     *                     type="string",
     *                     default="patch",
     *                     enum={"patch"},
     *                     description="این مقدار باید بصورت ثابت شود",
     *                 ),
     *                 @OA\Property(
     *                     property="package_id",
     *                     type="number",
     *                     description="شناسه پکیج"
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
    public function assignPackage(Request $request, $rahjoo)
    {
        return $this->rahjooService->assignPackage($request, $rahjoo);
    }

    /**
     * @OA\Get (
     *     path="/rahjoos/{id}/package-exercises",
     *     summary="لیست تمرین های رهجو بصورت صفحه بندی",
     *     description="",
     *     tags={"رهجو"},
     *     @OA\Parameter(
     *         description="شناسه رهجو",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
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
     *         description="وضعیت قفل",
     *         in="query",
     *         description="locked - notlocked",
     *         name="lock",
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
    public function packageExercises(Request $request, $rahjoo)
    {
        return $this->rahjooService->packageExercises($request, $rahjoo);
    }

    /**
     * @OA\Get (
     *     path="/rahjoos/{id}/exercises/{exercise}/questions",
     *     summary="لیست سوالات تمرین رهجو",
     *     description="",
     *     tags={"رهجو"},
     *     @OA\Parameter(
     *         description="شناسه رهجو",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Parameter(
     *         description="شناسه تمرین",
     *         in="path",
     *         name="exercise",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
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
     *         description="وضعیت قفل",
     *         in="query",
     *         description="locked - notlocked",
     *         name="lock",
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
    public function exerciseQuestions(Request $request, $rahjoo, $exercise)
    {
        return $this->rahjooService->packageQuestions($request, $rahjoo, $exercise);
    }

    /**
     * Delete a city.
     *
     * @OA\Delete(
     *     path="/rahjoos/{id}",
     *     summary="حذف رهجو",
     *     description="",
     *     tags={"رهجو"},
     *     @OA\Parameter(
     *         description="شناسه رهجو",
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
    public function destroy(Request $request, $user)
    {
        return $this->rahjooService->destroy($request, $user);
    }
}
