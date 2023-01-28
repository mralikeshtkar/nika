<?php

namespace App\Http\Controllers\V1\Api\Question;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Question\QuestionService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ApiQuestionController extends ApiBaseController
{
    /**
     * @var QuestionService
     */
    private QuestionService $questionService;

    /**
     * @param QuestionService $questionService
     */
    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    /**
     * Show question.
     *
     * @OA\Get (
     *     path="/questions/{id}",
     *     summary="دریافت اطلاعات سوال",
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
    public function show(Request $request, $question)
    {
        return $this->questionService->show($request, $question);
    }

    /**
     * Store a question.
     *
     * @OA\Post(
     *     path="/exercises/{id}/questions",
     *     summary="ثبت سوال",
     *     description="",
     *     tags={"سوال"},
     *     @OA\Parameter(
     *         description="شناسه تمرین",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
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
    public function store(Request $request, $exercise)
    {
        return $this->questionService->store($request, $exercise);
    }

    /**
     * @OA\Post(
     *     path="/questions/{id}/change-priority-question",
     *     summary="بروزرسانی چیدمان سوالات",
     *     description="",
     *     tags={"سوال"},
     *     @OA\Parameter(
     *         description="شناسه تمرین",
     *         in="path",
     *         name="id",
     *         example="1",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"_method","ids"},
     *                 @OA\Property(
     *                     property="_method",
     *                     type="string",
     *                     default="put",
     *                     enum={"put"},
     *                     description="این مقدار باید بصورت ثابت شود",
     *                 ),
     *                  @OA\Property(
     *                     property="ids",
     *                     type="array",
     *                     @OA\Items(type="number"),
     *                     description="شناسه های سوال"
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
    public function changePriorityQuestion(Request $request, $exercise)
    {
        return $this->questionService->changePriorityQuestion($request, $exercise);
    }

    /**
     * Show question.
     *
     * @OA\Get (
     *     path="/questions/{id}/files",
     *     summary="دریافت فایل های سوال",
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
    public function files(Request $request, $question)
    {
        return $this->questionService->files($request, $question);
    }

    /**
     * Store question file.
     *
     * @OA\Post(
     *     path="/questions/{id}/upload-file",
     *     summary="اپلود فایل سوال",
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
     *                  @OA\Property(
     *                      property="file",
     *                      type="string",
     *                      description="فایل",
     *                      format="binary",
     *                  ),
     *                 @OA\Property(
     *                     property="text",
     *                     type="string",
     *                     description="متن"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="ثبت با موفقیت انجام شد",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function uploadFile(Request $request, $question)
    {
        return $this->questionService->uploadFile($request, $question);
    }

    /**
     * Remove question file.
     *
     * @OA\Post(
     *     path="/questions/{id}/remove-file",
     *     summary="حذف فایل سوال",
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
     *                 required={"_method","id"},
     *                 @OA\Property(
     *                     property="_method",
     *                     type="string",
     *                     default="delete",
     *                     enum={"delete"},
     *                     description="این مقدار باید بصورت ثابت شود",
     *                 ),
     *                 @OA\Property(
     *                     property="id",
     *                     type="string",
     *                     description="شناسه فایل "
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="ثبت با موفقیت انجام شد",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function removeFile(Request $request, $question)
    {
        return $this->questionService->removeFile($request, $question);
    }

    /**
     * Update a question.
     *
     * @OA\Post(
     *     path="/questions/{id}",
     *     summary="بروزرسانی سوال",
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
    public function update(Request $request, $question)
    {
        return $this->questionService->update($request, $question);
    }

    /**
     * Chane question files priority.
     *
     * @OA\Post(
     *     path="/questions/{id}/change-file-priority",
     *     summary="بروزرسانی چیدمان فایل های سوال",
     *     description="باید تمام شناسه های فایل سوال بصورت ارایه ارسال شود",
     *     tags={"سوال"},
     *     @OA\Parameter(
     *         description="شناسه سوال",
     *         in="path",
     *         name="id",
     *         example="1",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"_method","ids"},
     *                 @OA\Property(
     *                     property="_method",
     *                     type="string",
     *                     default="put",
     *                     enum={"put"},
     *                     description="این مقدار باید بصورت ثابت شود",
     *                 ),
     *                  @OA\Property(
     *                     property="ids",
     *                     type="array",
     *                     @OA\Items(type="number"),
     *                     description="شناسه های فایل های سوال"
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
    public function changeFilePriority(Request $request, $question)
    {
        return $this->questionService->changeFilePriority($request, $question);
    }

    /**
     * Get question answer types as pagination.
     *
     * @OA\Get (
     *     path="/questions/{id}/answer-types",
     *     summary="لیست نوع های پاسخ سوال",
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
    public function answerTypes(Request $request, $question)
    {
        return $this->questionService->answerTypes($request, $question);
    }

    /**
     * Destroy a question.
     *
     * @OA\Delete(
     *     path="/questions/{id}",
     *     summary="بروزرسانی سوال",
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
    public function destroy(Request $request, $question)
    {
        return $this->questionService->destroy($request, $question);
    }
}
