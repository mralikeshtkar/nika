<?php

namespace App\Http\Controllers\V1\Api;

use App\Http\Resources\V1\Media\MediaResource;
use App\Http\Resources\V1\PaginationResource;
use App\Models\Media;
use App\Models\User;
use App\Responses\Api\ApiResponse;
use App\Traits\Media\HasMedia;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ApiUploadFileController extends ApiBaseController
{
    use HasMedia;

    const UPLOAD_FILE_COLLECTION_MEDIA = "upload file collection media";

    /**
     * @OA\Get (
     *     path="/upload/list",
     *     summary="دریافت فایل های اپلود شده",
     *     description="",
     *     tags={"آپلود فایل"},
     *     @OA\Parameter(
     *         description="شماره صفحه",
     *         in="query",
     *         name="page",
     *         required=true,
     *         example=1,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Parameter(
     *         description="تعداد نمایش در هر صفحه",
     *         in="query",
     *         name="perPage",
     *         example=10,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function index(Request $request)
    {
        $media = Media::query()->where('collection', self::UPLOAD_FILE_COLLECTION_MEDIA)->paginate($request->get('perPage', 10));
        $resource = PaginationResource::collection($media)->additional(['itemsResource' => MediaResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('media', $resource)
            ->send();
    }

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
        $media = Media::query()->findOrFail($media);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('media', new MediaResource($media))
            ->send();
    }

    /**
     * @OA\Post(
     *     path="/upload",
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
            ->setCollection(self::UPLOAD_FILE_COLLECTION_MEDIA)
            ->setExtension($extension)
            ->setBaseUrl(url('/'));
        $media = Media::query()->create([
            'user_id' => User::query()->inRandomOrder()->first()->id,
            'disk' => $this->getDisk(),
            'files' => $this->setDirectory('upload')->storeFile($request->file),
            'extension' => $this->getExtension(),
            'type' => $this->getType(),
            'collection' => $this->getCollection(),
            'base_url' => $this->getBaseUrl(),
        ]);
        return ApiResponse::message(trans("Mission accomplished"))
            ->addData('media', new MediaResource($media))
            ->send();
    }
}
