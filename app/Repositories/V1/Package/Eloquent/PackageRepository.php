<?php

namespace App\Repositories\V1\Package\Eloquent;

use App\Models\Media;
use App\Models\Package;
use App\Repositories\V1\BaseRepository;
use App\Repositories\V1\Package\Interfaces\PackageRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PackageRepository extends BaseRepository implements PackageRepositoryInterface
{
    public function __construct(Package $model)
    {
        parent::__construct($model);
    }


    /**
     * Filter pagination.
     *
     * @param Request $request
     * @return $this
     */
    public function filterPagination(Request $request): static
    {
        $this->model = $this->model->when($request->filled('title'), function (Builder $builder) use ($request) {
            $builder->where('title', '%' . $request->title . '%');
        });
        return $this;
    }

    /**
     * @param $package
     * @param $video
     * @return null
     */
    public function uploadVideo($package, $video)
    {
        return $video ? $package->setDisk(Media::MEDIA_PUBLIC_DISK)
            ->setDirectory(Package::MEDIA_DIRECTORY_VIDEOS)
            ->setCollection(Package::MEDIA_COLLECTION_VIDEO)
            ->addMedia($video) : null;
    }

    /**
     * @param $package
     * @param $intelligences
     * @return mixed
     */
    public function syncIntelligences($package, $intelligences): mixed
    {
        return $package->intelligences()->synce($intelligences);
    }

    /**
     * @param $package
     * @return void
     */
    public function destroyVideo($package)
    {
        if ($video = $package->video) $video->delete();
    }

}
