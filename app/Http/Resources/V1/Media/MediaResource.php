<?php

namespace App\Http\Resources\V1\Media;

use FFMpeg\FFProbe\DataMapping\Stream;
use Illuminate\Http\Resources\Json\JsonResource;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'model_type' => $this->resource->model_type,
            'model_id' => $this->resource->model_id,
            'disk' => $this->resource->disk,
            'extension' => $this->resource->extension,
            'type' => $this->resource->type,
            'stream' => $this->streamVideo(),
            'files' => collect($this->resource->files)->map(function ($item) {
                return $this->resource->base_url . '/' . $item;
            })->toArray(),
        ];
    }

    private function streamVideo()
    {
        /** @var Stream $stream */
        $stream=FFMpeg::fromDisk($this->resource->disk)->open($this->resource->files['original'])->getStreams()[0];

        return FFMpeg::fromDisk($this->resource->disk)->open($this->resource->files['original'])->getStreams()[0]->get('codec_name');
    }
}
