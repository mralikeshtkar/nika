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
     *     @OA\Parameter(
     *         description="نام",
     *         in="query",
     *         name="name",
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Parameter(
     *         description="مقدار 0 پکیج ندارند 1 پکیج دارند",
     *         in="query",
     *         name="has_package",
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
     * @OA\Post(
     *     path="/rahjoos/{id}/assign-rahyab/{user}",
     *     summary="ثبت رهیاب برای رهجو",
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
     *         description="شناسه کاربر",
     *         in="path",
     *         name="user",
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
     *                     default="patch",
     *                     enum={"patch"},
     *                     description="این مقدار باید بصورت ثابت شود",
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
    public function assignRahyab(Request $request, $rahjoo, $user)
    {
        return $this->rahjooService->assignRahyab($request, $rahjoo, $user);
    }

    /**
     * @OA\Post(
     *     path="/rahjoos/{id}/assign-rahnama/{user}",
     *     summary="ثبت رهیاب برای رهجو",
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
     *         description="شناسه کاربر",
     *         in="path",
     *         name="user",
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
     *                     default="patch",
     *                     enum={"patch"},
     *                     description="این مقدار باید بصورت ثابت شود",
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
    public function assignRahnama(Request $request, $rahjoo, $user)
    {
        return $this->rahjooService->assignRahnama($request, $rahjoo, $user);
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
     *     path="/rahjoos/{id}/exercise/{exercise}/questions",
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
     * @OA\Get (
     *     path="/rahjoos/{id}/exercise/{exercise}/question-single/{question}",
     *     summary="سوال تمرین رهجو",
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
     *         description="شناسه سوال",
     *         in="path",
     *         name="question",
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
    public function exerciseSingleQuestion(Request $request, $rahjoo, $exercise, $question)
    {
        return $this->rahjooService->exerciseSingleQuestion($request, $rahjoo, $exercise, $question);
    }

    /**
     * @OA\Post(
     *     path="/rahjoos/{rahjoo}/questions/{question}/question-points",
     *     summary="ثبت امتیاز برای رهجو",
     *     description="",
     *     tags={"رهجو"},
     *     @OA\Parameter(
     *         description="شناسه رهجو",
     *         in="path",
     *         name="rahjoo",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Parameter(
     *         description="شناسه سوال",
     *         in="path",
     *         name="question",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(property="points", type="array",
     *                      @OA\Items(
     *                          type="integer"
     *                      )
     *                  )
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
    public function storeQuestionPoints(Request $request, $rahjoo, $question)
    {
        return $this->rahjooService->storeQuestionPoints($request, $rahjoo, $question);
    }

    /**
     * @OA\Post(
     *     path="/rahjoos/{rahjoo}/questions/{question}/question-points-update",
     *     summary="بروزرسانی امتیاز برای رهجو",
     *     description="",
     *     tags={"رهجو"},
     *     @OA\Parameter(
     *         description="شناسه رهجو",
     *         in="path",
     *         name="rahjoo",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Parameter(
     *         description="شناسه سوال",
     *         in="path",
     *         name="question",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"_method","intelligence_point_id","point"},
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
     *                     description="شناسه امتیاز هوش"
     *                 ),
     *                 @OA\Property(
     *                     property="point",
     *                     type="number",
     *                     description="امتیاز"
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
    public function updateQuestionPoints(Request $request, $rahjoo, $question)
    {
        return $this->rahjooService->updateQuestionPoints($request, $rahjoo, $question);
    }

    /**
     * @OA\Get (
     *     path="/rahjoos/{rahjoo}/questions/{question}/question-points",
     *     summary="دریافت سوال همراه با امتیاز برای رهجو",
     *     description="",
     *     tags={"رهجو"},
     *     @OA\Parameter(
     *         description="شناسه رهجو",
     *         in="path",
     *         name="rahjoo",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Parameter(
     *         description="شناسه سوال",
     *         in="path",
     *         name="question",
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
    public function showQuestionPoints(Request $request, $rahjoo, $question)
    {
        return $this->rahjooService->showQuestionPoints($request, $rahjoo, $question);
    }

    /**
     * @OA\Post(
     *     path="/rahjoos/{id}/questions/{question}/comments",
     *     summary="ثبت نظر برای سوال",
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
     *         description="شناسه سوال",
     *         in="path",
     *         name="question",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"body"},
     *                 @OA\Property(
     *                     property="body",
     *                     type="string",
     *                     description="متن",
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
    public function storeQuestionComment(Request $request, $rahjoo, $question)
    {
        return $this->rahjooService->storeQuestionComment($request, $rahjoo, $question);
    }

    /**
     * @OA\Get (
     *     path="/rahjoos/{id}/questions/{question}/comments",
     *     summary="رهجو دریافت نظرات سوال",
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
     *         description="شناسه سوال",
     *         in="path",
     *         name="question",
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
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function questionComments(Request $request, $rahjoo, $question)
    {
        return $this->rahjooService->questionComments($request, $rahjoo, $question);
    }

    /**
     * @OA\Post(
     *     path="/rahjoos/{id}/intelligence-packages/{intelligencePackage}/comments",
     *     summary="ثبت نظر برای سوال",
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
     *         description="شناسه هوش پکیج",
     *         in="path",
     *         name="intelligencePackage",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"body"},
     *                 @OA\Property(
     *                     property="body",
     *                     type="string",
     *                     description="متن",
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
    public function storeIntelligencePackageComment(Request $request, $rahjoo, $intelligencePackage)
    {
        return $this->rahjooService->storeIntelligencePackageComment($request, $rahjoo, $intelligencePackage);
    }

    public function intelligencePackageComments(Request $request, $rahjoo, $intelligencePackage)
    {
        return $this->rahjooService->intelligencePackageComments($request, $rahjoo, $intelligencePackage);
    }

    /**
     * @OA\Get (
     *     path="/rahjoos/{id}/intelligence-rahnama",
     *     summary="دریافت رهنما هوش برای رهجو",
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
    public function intelligenceRahnama(Request $request, $rahjoo)
    {
        return $this->rahjooService->intelligenceRahnama($request, $rahjoo);
    }

    /**
     * @OA\Post(
     *     path="/rahjoos/{id}/intelligence-rahnama",
     *     summary="ثبت رهنما هوش برای رهجو",
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
     *                 required={"rahanma_id","intelligence_id"},
     *                 @OA\Property(
     *                     property="rahanma_id",
     *                     type="string",
     *                     description="شناسه رهنما",
     *                 ),
     *                 @OA\Property(
     *                     property="intelligence_id",
     *                     type="string",
     *                     description="شناسه هوش",
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
    public function storeIntelligenceRahnama(Request $request, $rahjoo)
    {
        return $this->rahjooService->storeIntelligenceRahnama($request, $rahjoo);
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

    /**
     * @OA\Get (
     *     path="/rahjoos/list/have-not-rahnama-rahyab",
     *     summary="لیست رهجو هایی که رهیاب یا رهنما ندارند بصورت صفحه بندی",
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
    public function haveNotRahnamaRahyab(Request $request)
    {
        return $this->rahjooService->haveNotRahnamaRahyab($request);
    }
}
