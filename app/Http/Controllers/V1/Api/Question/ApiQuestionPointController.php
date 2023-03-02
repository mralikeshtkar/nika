<?php

namespace App\Http\Controllers\V1\Api\Question;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Question\QuestionPointService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ApiQuestionPointController extends ApiBaseController
{
    /**
     * @var QuestionPointService
     */
    private QuestionPointService $questionPointService;

    /**
     * @param QuestionPointService $questionPointService
     */
    public function __construct(QuestionPointService $questionPointService)
    {
        $this->questionPointService = $questionPointService;
    }

    /**
     * @OA\Get (
     *     path="/questions/{id}/points",
     *     summary="دریافت ارزش های یک سوال",
     *     description="",
     *     tags={"سوال"},
     *     @OA\Parameter(
     *         description="شناسه سوال",
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
    public function index(Request $request, $question)
    {
        return $this->questionPointService->index($request, $question);
    }

    /**
     * @OA\Get (
     *     path="/questions/{id}/points/{rahjoo}/have-not",
     *     summary="دریافت ارزش های یک سوال که نمره داده نشده اند",
     *     description="",
     *     tags={"سوال"},
     *     @OA\Parameter(
     *         description="شناسه سوال",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Parameter(
     *         description="شناسه رهجو",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="rahjoo"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function haveNotPoint(Request $request, $question, $rahjoo)
    {
        return $this->questionPointService->haveNotPoint($request, $question,$rahjoo);
    }

    /**
     * @OA\Post(
     *     path="/questions/{id}/points",
     *     summary="ثبت ارزش برای سوال",
     *     description="",
     *     tags={"سوال"},
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
     *                 required={"intelligence_point_id","max_point"},
     *                 @OA\Property(
     *                     property="intelligence_point_id",
     *                     type="number",
     *                     description="شناسه ارزش",
     *                 ),
     *                 @OA\Property(
     *                     property="max_point",
     *                     type="number",
     *                     description="میزان ارزش",
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="توضیحات",
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
        return $this->questionPointService->store($request, $question);
    }

    /**
     * @OA\Post(
     *     path="/questions/{id}/update-points",
     *     summary="بروزرسانی ارزش برای سوال",
     *     description="",
     *     tags={"سوال"},
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
     *                 required={"_method","intelligence_point_id","max_point"},
     *                 @OA\Property(
     *                     property="_method",
     *                     type="string",
     *                     default="put",
     *                     enum={"put"},
     *                     description="این مقدار باید بصورت ثابت شود",
     *                 ),
     *                 @OA\Property(
     *                     property="intelligence_point_id",
     *                     type="number",
     *                     description="شناسه ارزش",
     *                 ),
     *                 @OA\Property(
     *                     property="max_point",
     *                     type="number",
     *                     description="میزان ارزش",
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="توضیحات",
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
    public function update(Request $request, $question)
    {
        return $this->questionPointService->update($request, $question);
    }

    /**
     * @OA\Post(
     *     path="/questions/{id}/destroy-points",
     *     summary="جذف ارزش سوال",
     *     description="",
     *     tags={"سوال"},
     *     @OA\Parameter(
     *         description="سوال",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"_method","intelligence_point_id"},
     *                 @OA\Property(
     *                     property="_method",
     *                     type="string",
     *                     default="delete",
     *                     enum={"delete"},
     *                     description="این مقدار باید بصورت ثابت شود",
     *                 ),
     *                 @OA\Property(
     *                     property="intelligence_point_id",
     *                     type="number",
     *                     description="شناسه ارزش",
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
    public function destroy(Request $request, $question)
    {
        return $this->questionPointService->destroy($request, $question);
    }
}
