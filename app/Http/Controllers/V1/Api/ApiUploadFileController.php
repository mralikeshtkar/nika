<?php

namespace App\Http\Controllers\V1\Api;

use App\Http\Resources\V1\Media\MediaResource;
use App\Models\Media;
use App\Models\User;
use App\Responses\Api\ApiResponse;
use App\Traits\Media\HasMedia;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ApiUploadFileController extends ApiBaseController
{
    use HasMedia;

    /**
     * @OA\Get(
     *     path="/upload/{id}",
     *     summary="دریافت فایل اپلود شده",
     *     description="",
     *     tags={"آپلود فایل"},
     *     @OA\Parameter(
     *         description="شناسه مدیا",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function show(Request $request, $media)
    {
        $media=Media::query()->findOrFail($media);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('media',new MediaResource($media))
            ->send();
    }

    /**
     * @OA\Post(
     *     path="/upload/file",
     *     summary="اپلود فایل",
     *     description="",
     *     tags={"آپلود فایل"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"file"},
     *                  @OA\Property(
     *                      property="file",
     *                      type="string",
     *                      description="فایل",
     *                      format="binary",
     *                  ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="ثبت با موفقیت انجام شد",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function file(Request $request)
    {
        ApiResponse::validate($request->all(), [
            'file' => ['required', 'file']
        ]);
        $extension = strtolower($request->file->extension());
        $this->setType($this->getFileType($extension))
            ->setExtension($extension)
            ->setBaseUrl(url('/'));
        $media=Media::query()->create([
            'user_id' => User::query()->inRandomOrder()->first()->id,
            'disk' => $this->getDisk(),
            'files' => $this->setDirectory('upload')->storeFile($request->file),
            'extension' => $this->getExtension(),
            'type' => $this->getType(),
            'collection' => $this->getCollection(),
            'base_url' => $this->getBaseUrl(),
        ]);
        return ApiResponse::message(trans("Mission accomplished"))
            ->addData('media',new MediaResource($media))
            ->send();
    }
}
