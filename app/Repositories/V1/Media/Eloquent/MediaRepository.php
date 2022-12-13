<?php

namespace App\Repositories\V1\Media\Eloquent;

use App\Models\Media;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Media\Interfaces\MediaRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class MediaRepository extends BaseRepository implements MediaRepositoryInterface
{
    public function __construct(Media $model)
    {
        parent::__construct($model);
    }

    /**
     * @param $model
     * @return void
     */
    public function destroy($model)
    {
        if ($model) parent::destroy($model);
    }

    /**
     * @param array $models
     * @return void
     */
    public function destroyAll(array $models)
    {
        if ($models && count($models)) {
            foreach ($models as $model) $model->delete();
        }
    }

}
