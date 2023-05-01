<?php

namespace App\Http\Controllers\V1\Api\Ticket;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Ticket\TicketService;
use Illuminate\Http\Request;

class ApiTicketController extends ApiBaseController
{
    /**
     * @var TicketService
     */
    private TicketService $ticketService;

    /**
     * @param TicketService $ticketService
     */
    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    /**
     * @OA\Get (
     *     path="/tickets",
     *     summary="لیست تیکت ها بصورت صفحه بندی",
     *     description="",
     *     tags={"تیکت"},
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
     *         description="وضعیت: open,close,canceled",
     *         in="query",
     *         name="status",
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Parameter(
     *         description="قدیمی ترین. مقدار 1 ------ پیش فرض جدیدترین است",
     *         in="query",
     *         name="oldest",
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات با موفقیت انجام شد",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function index(Request $request)
    {
        return $this->ticketService->index($request);
    }

    /**
     * @OA\Post(
     *     path="/tickets",
     *     summary="ثبت تیکت",
     *     description="",
     *     tags={"تیکت"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title","body"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="عنوان"
     *                 ),
     *                 @OA\Property(
     *                     property="body",
     *                     type="number",
     *                     description="متن"
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
        return $this->ticketService->store($request);
    }

    /**
     * @OA\Get (
     *     path="/tickets/{id}",
     *     summary="نمایش تیکت",
     *     description="",
     *     tags={"تیکت"},
     *     @OA\Parameter(
     *         description="شناسه تیکت",
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
    public function show(Request $request, $ticket)
    {
        return $this->ticketService->show($request,$ticket);
    }

    /**
     * @OA\Post(
     *     path="/tickets/{id}",
     *     summary="بروزرسانی تیکت",
     *     description="",
     *     tags={"تیکت"},
     *     @OA\Parameter(
     *         description="شناسه تیکت",
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
     *                     property="title",
     *                     type="string",
     *                     description="عنوان"
     *                 ),
     *                 @OA\Property(
     *                     property="status",
     *                     type="string",
     *                     description="وضعیت های open,close,canceled میتواند باشد"
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
    public function update(Request $request, $ticket)
    {
        return $this->ticketService->update($request, $ticket);
    }
}
