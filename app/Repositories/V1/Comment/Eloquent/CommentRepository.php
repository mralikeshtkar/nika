<?php

namespace App\Repositories\V1\Comment\Eloquent;

use App\Models\Comment;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Comment\Interfaces\CommentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class CommentRepository extends BaseRepository implements CommentRepositoryInterface
{
    public function __construct(Comment $model)
    {
        parent::__construct($model);
    }
}
