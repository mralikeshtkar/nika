<?php

namespace App\Http\Controllers\V1\Api\Storeroom;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Storeroom\StoreroomService;
use Illuminate\Http\Request;

class ApiStoreroomController extends ApiBaseController
{
    /**
     * @var StoreroomService
     */
    private StoreroomService $storeroomService;

    /**
     * @param StoreroomService $storeroomService
     */
    public function __construct(StoreroomService $storeroomService)
    {
        $this->storeroomService = $storeroomService;
    }

    /**
     * @OA\Get (
     *     path="/storerooms",
     *     summary="لیست استان ها بصورت صفحه بندی",
     *     description="",
     *     tags={"استان"},
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
     *         description="نام",
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
        return $this->storeroomService->index($request);
    }
}
