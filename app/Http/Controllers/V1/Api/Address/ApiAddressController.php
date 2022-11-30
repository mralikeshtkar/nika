<?php

namespace App\Http\Controllers\V1\Api\Address;

use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\Address\AddressService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ApiAddressController extends ApiBaseController
{
    private AddressService $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    /**
     * Get addresses as pagination.
     *
     * @OA\Get (
     *     path="/addresses",
     *     summary="لیست آدرس ها بصورت صفحه بندی",
     *     description="",
     *     tags={"آدرس"},
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
     *     @OA\Parameter(
     *         description="شناسه صاحب آدرس. فقط کاربری که مدیر و دسترسی مجاز را دارد میتواند بر اساس این فیلد جستجو کند",
     *         in="query",
     *         name="user_id",
     *         required=false,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Parameter(
     *         description="نام صاحب آدرس. فقط کاربری که مدیر و دسترسی مجاز را دارد میتواند بر اساس این فیلد جستجو کند",
     *         in="query",
     *         name="name",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Parameter(
     *         description="شماره تلفن آدرس",
     *         in="query",
     *         name="phone_number",
     *         required=false,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Parameter(
     *         description="نوع. Home:خانه Office:دفتر",
     *         in="query",
     *         name="type",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             enum={"Home", "Office"}
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="چیدمان. oldest:قدیمی ترین latest:جدیدترین",
     *         in="query",
     *         name="sort",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             default="latest",
     *             enum={"oldest", "latest"}
     *         )
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
        return $this->addressService->index($request);
    }

    /**
     * Store an address.
     *
     * @OA\Post(
     *     path="/addresses",
     *     summary="ثبت آدرس",
     *     description="کاربری که  مجوز مدیریت آدرس ها یا ایجاد آدرس را دارد میتواند با ارسال user_id برای دیگران هم آدرس ثبت کند.",
     *     tags={"آدرس"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"address","city_id","postal_code","phone_number","type"},
     *                 @OA\Property(
     *                     property="user_id",
     *                     type="number",
     *                     description="شناسه کاربر"
     *                 ),
     *                 @OA\Property(
     *                     property="city_id",
     *                     type="number",
     *                     description="شناسه شهر"
     *                 ),
     *                 @OA\Property(
     *                     property="address",
     *                     type="string",
     *                     description="آدرس"
     *                 ),
     *                 @OA\Property(
     *                     property="postal_code",
     *                     type="number",
     *                     description="کد پستی. بدون خط تیره مثال: 5717858731"
     *                 ),
     *                 @OA\Property(
     *                     property="phone_number",
     *                     type="number",
     *                     description="شماره تلفن یا شماره موبایل. تفلن ثابت باید با کد شهر وارد شود بدون خط تیره مثال: 02162999977"
     *                 ),
     *                 @OA\Property(
     *                     property="type",
     *                     type="string",
     *                     enum={"Home","Office"},
     *                     description="Home: خانه - Office: دفتر",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="ثبت با موفقیت انجام شد",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function store(Request $request)
    {
        return $this->addressService->store($request);
    }

    /**
     * Update an address.
     *
     * @OA\Post(
     *     path="/addresses/{id}",
     *     summary="بروزرسانی آدرس",
     *     description="",
     *     tags={"آدرس"},
     *     @OA\Parameter(
     *         description="شناسه آدرس",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"_method","address","city_id","postal_code","phone_number","type"},
     *                 @OA\Property(
     *                     property="_method",
     *                     type="string",
     *                     default="put",
     *                     enum={"put"},
     *                     description="این مقدار باید بصورت ثابت شود",
     *                 ),
     *                 @OA\Property(
     *                     property="city_id",
     *                     type="number",
     *                     description="شناسه شهر"
     *                 ),
     *                 @OA\Property(
     *                     property="address",
     *                     type="string",
     *                     description="آدرس"
     *                 ),
     *                 @OA\Property(
     *                     property="postal_code",
     *                     type="number",
     *                     description="کد پستی. بدون خط تیره مثال: 5717858731"
     *                 ),
     *                 @OA\Property(
     *                     property="phone_number",
     *                     type="number",
     *                     description="شماره تلفن یا شماره موبایل. تفلن ثابت باید با کد شهر وارد شود بدون خط تیره مثال: 02162999977"
     *                 ),
     *                 @OA\Property(
     *                     property="type",
     *                     type="string",
     *                     enum={"Home","Office"},
     *                     description="Home: خانه - Office: دفتر",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function update(Request $request, $address)
    {
        return $this->addressService->update($request, $address);
    }

    /**
     * Delete a City.
     *
     * @OA\Delete(
     *     path="/addresses/{id}",
     *     summary="حذف آدرس",
     *     description="",
     *     tags={"آدرس"},
     *     @OA\Parameter(
     *         description="شناسه آدرس",
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
    public function destroy(Request $request, $address)
    {
        return $this->addressService->destroy($request, $address);
    }
}
