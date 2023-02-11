<?php

namespace App\Traits\Comment;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasComment
{
    /**
     * @param $data
     * @return Model
     */
    public function storeComment($data): Model
    {
        return $this->comments()->create($data);
    }

    /**
     * @return MorphMany
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, Comment::POLY_MORPHIC_KEY);
    }
}
