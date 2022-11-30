<?php

namespace App\Http\Controllers\V1\Api\PsychologicalQuestion;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\PsychologicalQuestion\PsychologicalQuestionService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ApiPsychologicalQuestionController extends ApiBaseController
{
    private PsychologicalQuestionService $psychologicalQuestionService;

    /**
     * @param PsychologicalQuestionService $psychologicalQuestionService
     */
    public function __construct(PsychologicalQuestionService $psychologicalQuestionService)
    {
        $this->psychologicalQuestionService = $psychologicalQuestionService;
    }

    /**
     * Store a psychological question.
     *
     * @OA\Post(
     *     path="/psychological-questions/{id}",
     *     summary="ثبت سوال روانشناسی",
     *     description="",
     *     tags={"سوالات روانشناسی"},
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
     *                 @OA\Property(
     *                     property="favourite_job_id",
     *                     type="string",
     *                     description="نام شغل مورد علاقه رهجو. کاربر بتواند نام هم خودش یا از لیست شغل ها انتخاب نماید"
     *                 ),
     *                 @OA\Property(
     *                     property="parent_favourite_job_id",
     *                     type="string",
     *                     description="نام شغل مورد علاقه والدین رهجو. کاربر بتواند نام هم خودش یا از لیست شغل ها انتخاب نماید"
     *                 ),
     *                 @OA\Property(
     *                     property="negative_positive_points",
     *                     type="string",
     *                     description="نقاط مثبت و منفی"
     *                 ),
     *                 @OA\Property(
     *                     property="favourites",
     *                     type="string",
     *                     description="علاقه مندی ها"
     *                 ),
     *                  @OA\Property(
     *                     property="skills[]",
     *                     type="array",
     *                     @OA\Items(type="number"),
     *                     description="مهارت ها (شناسه مهارت)"
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
    public function store(Request $request,$rahjoo)
    {
        return $this->psychologicalQuestionService->store($request, $rahjoo, "salam");
    }
}
