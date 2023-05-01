<?php

namespace App\Services\V1\Ticket;

use App\Enums\Ticket\TicketStatus;
use App\Models\Ticket;
use App\Repositories\V1\Ticket\Interfaces\TicketReplyRepositoryInterface;
use App\Repositories\V1\Ticket\Interfaces\TicketRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TicketReplyService extends BaseService
{
    /**
     * @var TicketRepositoryInterface
     */
    private TicketRepositoryInterface $ticketRepository;
    /**
     * @var TicketReplyRepositoryInterface
     */
    private TicketReplyRepositoryInterface $ticketReplyRepository;

    /**
     * @param TicketReplyRepositoryInterface $ticketReplyRepository
     * @param TicketRepositoryInterface $ticketRepository
     */
    public function __construct(TicketReplyRepositoryInterface $ticketReplyRepository, TicketRepositoryInterface $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
        $this->ticketReplyRepository = $ticketReplyRepository;
    }

    public function store(Request $request, $ticket): mixed
    {
        $ticket = $this->ticketRepository->findOrFailById($ticket);
        abort_if($ticket->status != TicketStatus::Open, ApiResponse::message(trans("To register the answer, the ticket must be open"), Response::HTTP_BAD_REQUEST)->send());
        ApiResponse::validate($request->all(), [
            'body' => ['required', 'string'],
        ]);
        try {
            return DB::transaction(function () use ($request, $ticket) {
                $ticket->replies()->create([
                    'user_id' => $request->user()->id,
                    'body' => $request->body,
                ]);
                return ApiResponse::message(trans("The :attribute was successfully registered", ['attribute' => trans('TicketReply')]), Response::HTTP_CREATED)->send();
            });
        } catch (Throwable $e) {
            return ApiResponse::error(trans("Internal server error"))->send();
        }
    }

    public function update(Request $request, $ticket, $ticketReply)
    {
        $ticket = $this->ticketRepository->findOrFailById($ticket);
        $ticketReply = $ticket->replies()->findOrFail($ticketReply);
        ApiResponse::validate($request->all(), [
            'body' => ['required', 'string'],
        ]);
        try {
            return DB::transaction(function () use ($request, $ticketReply) {
                $ticketReply->update($ticketReply, [
                    'body' => $request->body,
                ]);
                return ApiResponse::message(trans("The :attribute was successfully updated", ['attribute' => trans('TicketReply')]))->send();
            });
        } catch (Throwable $e) {
            return ApiResponse::error(trans("Internal server error"))->send();
        }
    }
}
