<?php

namespace App\Http\Controllers\V1\Api\IntelligencePointName;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\IntelligencePointName\IntelligencePointNameService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ApiIntelligencePointNameController extends ApiBaseController
{
    /**
     * @var IntelligencePointNameService
     */
    private IntelligencePointNameService $intelligencePointNameService;

    /**
     * @param IntelligencePointNameService $intelligencePointNameService
     */
    public function __construct(IntelligencePointNameService $intelligencePointNameService)
    {
        $this->intelligencePointNameService = $intelligencePointNameService;
    }

    /**
     * Store an intelligence point name.
     *
     * @OA\Post(
     *     path="/intelligence-point-names",
     *     summary="ثبت نام های امتیاز هوش",
     *     description="",
     *     tags={"نام های امتیاز هوش"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="نام"
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
        return $this->intelligencePointNameService->store($request);
    }

    public function update(Request $request, $intelligencePointName)
    {
        return $this->intelligencePointNameService->update($request,$intelligencePointName);
    }
}
