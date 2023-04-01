<?php

namespace App\Repositories\V1\SupportComment\Eloquent;

use App\Models\SupportComment;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\SupportComment\Interfaces\SupportCommentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class SupportCommentRepository extends BaseRepository implements SupportCommentRepositoryInterface
{
    public function __construct(SupportComment $model)
    {
        parent::__construct($model);
    }
}
