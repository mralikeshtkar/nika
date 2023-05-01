<?php

namespace App\Http\Controllers\V1\Api\Ticket;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Ticket\TicketReplyService;
use App\Services\V1\Ticket\TicketService;
use Illuminate\Http\Request;

class ApiTicketReplyController extends ApiBaseController
{
    /**
     * @var TicketReplyService
     */
    private TicketReplyService $ticketReplyService;

    /**
     * @param TicketReplyService $ticketReplyService
     */
    public function __construct(TicketReplyService $ticketReplyService)
    {

        $this->ticketReplyService = $ticketReplyService;
    }

    /**
     * @OA\Post(
     *     path="/tickets/{ticket}/replies",
     *     summary="ثبت پاسخ تیکت",
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
     *                 required={"body"},
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
    public function store(Request $request,$ticket)
    {
        return $this->ticketReplyService->store($request,$ticket);
    }

    /**
     * @OA\Post(
     *     path="/tickets/{id}/replies/{reply}",
     *     summary="بروزرسانی پاسخ تیکت",
     *     description="",
     *     tags={"تیکت"},
     *     @OA\Parameter(
     *         description="شناسه تیکت",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Parameter(
     *         description="شناسه پاسخ تیکت",
     *         in="path",
     *         name="reply",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"_method","body"},
     *                 @OA\Property(
     *                     property="_method",
     *                     type="string",
     *                     default="put",
     *                     enum={"put"},
     *                     description="این مقدار باید بصورت ثابت شود",
     *                 ),
     *                 @OA\Property(
     *                     property="body",
     *                     type="string",
     *                     description="متن"
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
    public function update(Request $request,$ticket,$ticketReply)
    {
        return $this->ticketReplyService->store($request,$ticket,$ticketReply);
    }
}
