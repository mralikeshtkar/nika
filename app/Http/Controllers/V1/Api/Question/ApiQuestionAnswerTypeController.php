<?php

namespace App\Http\Controllers\V1\Api\Question;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Question\QuestionAnswerTypeService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ApiQuestionAnswerTypeController extends ApiBaseController
{
    /**
     * @var QuestionAnswerTypeService
     */
    private QuestionAnswerTypeService $questionAnswerTypeService;

    /**
     * @param QuestionAnswerTypeService $questionAnswerTypeService
     */
    public function __construct(QuestionAnswerTypeService $questionAnswerTypeService)
    {
        $this->questionAnswerTypeService = $questionAnswerTypeService;
    }

    /**
     * Store a question answer type..
     *
     * @OA\Post(
     *     path="/questions/{id}/answer-types",
     *     summary="ثبت نوع جواب سوال",
     *     description="",
     *     tags={"انواع جواب سوال"},
     *     @OA\Parameter(
     *         description="شناسه سوال",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"type"},
     *                 @OA\Property(
     *                     property="type",
     *                     type="string",
     *                     description="نوع",
     *                     enum={"audio", "text", "video","image"}
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
    public function store(Request $request, $question)
    {
        return $this->questionAnswerTypeService->store($request, $question);
    }

    /**
     * Update a question answer type..
     *
     * @OA\Post(
     *     path="/question-answer-types/{id}",
     *     summary="بروزرسانی نوع جواب سوال",
     *     description="",
     *     tags={"انواع جواب سوال"},
     *     @OA\Parameter(
     *         description="شناسه نوع جواب سوال",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"_method","type"},
     *                 @OA\Property(
     *                     property="_method",
     *                     type="string",
     *                     default="put",
     *                     enum={"put"},
     *                     description="این مقدار باید بصورت ثابت شود",
     *                 ),
     *                 @OA\Property(
     *                     property="type",
     *                     type="string",
     *                     description="نوع",
     *                     enum={"audio", "text", "video","image"}
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
    public function update(Request $request, $questionAnswerType)
    {
        return $this->questionAnswerTypeService->update($request, $questionAnswerType);
    }

    /**
     * Chane question answer type priority.
     *
     * @OA\Post(
     *     path="/questions/{id}/answer-types/change-priority",
     *     summary="بروزرسانی چیدمان نوع جواب سوال",
     *     description="باید تمام شناسه های نوع جواب سوال بصورت ارایه ارسال شود",
     *     tags={"انواع جواب سوال"},
     *     @OA\Parameter(
     *         description="شناسه سوال",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"_method","answer_type_ids"},
     *                 @OA\Property(
     *                     property="_method",
     *                     type="string",
     *                     default="put",
     *                     enum={"put"},
     *                     description="این مقدار باید بصورت ثابت شود",
     *                 ),
     *                  @OA\Property(
     *                     property="answer_type_ids",
     *                     type="array",
     *                     @OA\Items(type="number"),
     *                     description="شناسه های نوع جواب سوال"
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
    public function changePriority(Request $request, $question)
    {
        return $this->questionAnswerTypeService->changePriority($request, $question);
    }

    /**
     * Destroy a question answer type.
     *
     * @OA\Delete(
     *     path="/question-answer-types/{id}",
     *     summary="حدف نوع جواب سوال",
     *     description="",
     *     tags={"انواع جواب سوال"},
     *     @OA\Parameter(
     *         description="شناسه نوع جواب سوال",
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
    public function destroy(Request $request, $questionAnswerType)
    {
        return $this->questionAnswerTypeService->destroy($request, $questionAnswerType);
    }
}
