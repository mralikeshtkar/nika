<?php

namespace App\Repositories\V1\Ticket\Eloquent;

use App\Models\TicketReply;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Ticket\Interfaces\TicketReplyRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class TicketReplyRepository extends BaseRepository implements TicketReplyRepositoryInterface
{
    public function __construct(TicketReply $model)
    {
        parent::__construct($model);
    }
}
