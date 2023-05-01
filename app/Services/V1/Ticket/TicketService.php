<?php

namespace App\Services\V1\Ticket;

use App\Enums\Ticket\TicketStatus;
use App\Http\Resources\V1\PaginationResource;
use App\Http\Resources\V1\Ticket\TicketResource;
use App\Models\Ticket;
use App\Repositories\V1\Ticket\Interfaces\TicketRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TicketService extends BaseService
{
    /**
     * @var TicketRepositoryInterface
     */
    private TicketRepositoryInterface $ticketRepository;

    /**
     * @param TicketRepositoryInterface $ticketRepository
     */
    public function __construct(TicketRepositoryInterface $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $tickets = $this->ticketRepository->with(['user:id,first_name,last_name,mobile'])
            ->withLastReplyUserId()
            ->filterPagination($request)
            ->paginate($request->get('perPage',15));
        $tickets->setCollection($tickets->getCollection()->transform(function($item){
            $item->is_answered = $item->user_id != $item->last_reply_user_id;
            return $item;
        }));
        $resource = PaginationResource::make($tickets)->additional(['itemsResource' => TicketResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('tickets', $resource)
            ->send();
    }

    /**
     * @param Request $request
     * @return JsonResponse|mixed
     */
    public function store(Request $request): mixed
    {
        ApiResponse::validate($request->all(), [
            'title' => ['required', 'string'],
            'body' => ['required', 'string'],
        ]);
        try {
            return DB::transaction(function () use ($request) {
                $ticket = $this->ticketRepository->create([
                    'user_id' => $request->user()->id,
                    'title' => $request->title,
                ]);
                $ticket->replies()->create([
                    'user_id' => $request->user()->id,
                    'body' => $request->body,
                ]);
                return ApiResponse::message(trans("The :attribute was successfully registered", ['attribute' => trans('Ticket')]), Response::HTTP_CREATED)
                    ->send();
            });
        } catch (Throwable $e) {
            return ApiResponse::error(trans("Internal server error"))
                ->addError('error', $e->getMessage())
                ->send();
        }
    }

    /**
     * @param Request $request
     * @param $ticket
     * @return JsonResponse
     */
    public function show(Request $request, $ticket): JsonResponse
    {
        $ticket = $this->ticketRepository->with(['user:id,first_name,last_name,mobile', 'replies' => function ($q) {
            $q->select(['id', 'user_id','ticket_id', 'body', 'created_at'])->with('user:id,first_name,last_name,mobile');
        }])->findOrFailById($ticket);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('ticket', new TicketResource($ticket))
            ->send();
    }

    /**
     * @param Request $request
     * @param $ticket
     * @return JsonResponse|mixed
     */
    public function update(Request $request, $ticket): mixed
    {
        $ticket = $this->ticketRepository->findOrFailById($ticket);
        ApiResponse::validate($request->all(), [
            'title' => ['nullable', 'string'],
            'status' => ['nullable', new EnumValue(TicketStatus::class)],
        ]);
        try {
            return DB::transaction(function () use ($request, $ticket) {
                $this->ticketRepository->update($ticket, collect()->when($request->filled('title'), function (Collection $collection) use ($request) {
                    $collection->put('title', $request->title);
                })->when($request->filled('status'), function (Collection $collection) use ($request) {
                    $collection->put('status', $request->status);
                })->toArray());
                return ApiResponse::message(trans("The :attribute was successfully updated", ['attribute' => trans('Ticket')]))->send();
            });
        } catch (Throwable $e) {
            return ApiResponse::error(trans("Internal server error"))
                ->addError('error', $e->getMessage())
                ->send();
        }
    }
}
