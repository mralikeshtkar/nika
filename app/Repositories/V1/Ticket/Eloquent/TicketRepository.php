<?php

namespace App\Repositories\V1\Ticket\Eloquent;

use App\Enums\Ticket\TicketStatus;
use App\Models\Ticket;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Ticket\Interfaces\TicketRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TicketRepository extends BaseRepository implements TicketRepositoryInterface
{
    public function __construct(Ticket $model)
    {
        parent::__construct($model);
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function filterPagination(Request $request): static
    {
        $this->model->when($request->filled('oldest'), function ($q) {
            $q->oldest();
        }, function ($q) {
            $q->latest();
        })->when($request->filled('status') && in_array(strtolower($request->status), TicketStatus::asArray()), function ($q) use ($request) {
            $q->where('status', strtolower($request->status));
        });
        return $this;
    }
}
