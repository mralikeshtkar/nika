<?php

namespace App\Services\V1\Media;

use App\Repositories\V1\Media\Interfaces\MediaRepositoryInterface;
use App\Services\V1\BaseService;
use App\Services\V1\Media\Stream\MediaStream;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MediaService extends BaseService
{
    /**
     * @var MediaRepositoryInterface
     */
    private MediaRepositoryInterface $mediaRepository;

    /**
     * @param MediaRepositoryInterface $mediaRepository
     */
    public function __construct(MediaRepositoryInterface $mediaRepository)
    {
        $this->mediaRepository = $mediaRepository;
    }

    /**
     * @param Request $request
     * @param $media
     * @param $file
     * @return StreamedResponse
     */
    public function download(Request $request, $media,$file): StreamedResponse
    {
        $media = $this->mediaRepository->select(['id','disk','files'])->findOrFailById($media);
        $filePath = Storage::disk($media->disk)->path($media->files[$file]);
        return MediaStream::stream($filePath, File::name($filePath) . "." . File::extension($filePath));
    }
}
