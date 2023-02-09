<?php

namespace App\Http\Controllers\V1\Api\User;

use App\Exceptions\User\UserAccountIsInactiveException;
use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\User\UserService;
use Exception;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ApiUserController extends ApiBaseController
{

    /**
     * @var UserService
     */
    private UserService $userService;

    /**
     * UserController constructor.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @OA\Get (
     *     path="/users",
     *     summary="لیست کاربران بصورت صفحه بندی",
     *     description="",
     *     tags={"کاربر"},
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
     *         description="جستجوی نام",
     *         in="query",
     *         name="name",
     *         required=false,
     *         @OA\Schema(type="srting"),
     *     ),
     *     @OA\Parameter(
     *         description="جستجوی موبایل",
     *         in="query",
     *         name="mobile",
     *         required=false,
     *         @OA\Schema(type="srting"),
     *     ),
     *     @OA\Parameter(
     *         description="جستجوی نقش کاربری (نام انگلیسی)",
     *         in="query",
     *         name="role",
     *         required=false,
     *         @OA\Schema(type="srting"),
     *     ),
     *     @OA\Parameter(
     *         description="کد ملی",
     *         in="query",
     *         name="national_code",
     *         required=false,
     *         @OA\Schema(type="srting"),
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
        return $this->userService->index($request);
    }

    /**
     * @OA\Get (
     *     path="/users/only-rahjoos",
     *     summary="لیست کاربران که رهجو بصورت صفحه بندی",
     *     description="",
     *     tags={"کاربر"},
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
     *         description="جستجوی نام",
     *         in="query",
     *         name="name",
     *         required=false,
     *         @OA\Schema(type="srting"),
     *     ),
     *     @OA\Parameter(
     *         description="جستجوی موبایل",
     *         in="query",
     *         name="mobile",
     *         required=false,
     *         @OA\Schema(type="srting"),
     *     ),
     *     @OA\Parameter(
     *         description="کد ملی",
     *         in="query",
     *         name="national_code",
     *         required=false,
     *         @OA\Schema(type="srting"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function onlyRahjoos(Request $request)
    {
        return $this->userService->onlyRahjoos($request);
    }

    /**
     * @OA\Post(
     *     path="/users/{user}/rahnama/intelligences",
     *     summary="ثبت هوش ها برای رهنما",
     *     description="",
     *     tags={"رهنما"},
     *     @OA\Parameter(
     *         description="شناسه کاربر",
     *         in="path",
     *         name="user",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"intelligences[]"},
     *                 @OA\Property(
     *                     property="intelligences[]",
     *                     type="array",
     *                     collectionFormat="multi",
     *                     @OA\Items(type="number", format="id")
     *                 ),
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
    public function storeRahnamaIntelligences(Request $request, $user)
    {
        return $this->userService->storeRahnamaIntelligences($request,$user);
    }

    /**
     * @OA\Get(
     *     path="/users/{user}/rahnama",
     *     summary="دریافت اطلاعات هوش",
     *     description="",
     *     tags={"رهنما"},
     *     @OA\Parameter(
     *         description="شناسه کاربر",
     *         in="path",
     *         name="user",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="ثبت با موفقیت انجام شد",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function rahnama(Request $request, $user)
    {
        return $this->userService->rahnama($request,$user);
    }

    /**
     * @OA\Get (
     *     path="/users/only-rahnama",
     *     summary="لیست کاربران که رهنما بصورت صفحه بندی",
     *     description="",
     *     tags={"کاربر"},
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
     *         description="جستجوی نام",
     *         in="query",
     *         name="name",
     *         required=false,
     *         @OA\Schema(type="srting"),
     *     ),
     *     @OA\Parameter(
     *         description="جستجوی موبایل",
     *         in="query",
     *         name="mobile",
     *         required=false,
     *         @OA\Schema(type="srting"),
     *     ),
     *     @OA\Parameter(
     *         description="کد ملی",
     *         in="query",
     *         name="national_code",
     *         required=false,
     *         @OA\Schema(type="srting"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function onlyRahnama(Request $request)
    {
        return $this->userService->onlyRahnama($request);
    }

    /**
     * @OA\Get (
     *     path="/users/only-rahyab",
     *     summary="لیست کاربران که رهیاب بصورت صفحه بندی",
     *     description="",
     *     tags={"کاربر"},
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
     *         description="جستجوی نام",
     *         in="query",
     *         name="name",
     *         required=false,
     *         @OA\Schema(type="srting"),
     *     ),
     *     @OA\Parameter(
     *         description="جستجوی موبایل",
     *         in="query",
     *         name="mobile",
     *         required=false,
     *         @OA\Schema(type="srting"),
     *     ),
     *     @OA\Parameter(
     *         description="کد ملی",
     *         in="query",
     *         name="national_code",
     *         required=false,
     *         @OA\Schema(type="srting"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function onlyRahyab(Request $request)
    {
        return $this->userService->onlyRahyab($request);
    }

    /**
     * Get current user.
     *
     * @OA\Get(
     *     path="/user",
     *     summary="دریافت اطلاعات کاربر لاگین شده",
     *     description="",
     *     tags={"کاربر"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="حساب کاربر مسدود میباشد",
     *         @OA\JsonContent()
     *     )
     * )
     */
    public function currentUser(Request $request)
    {
        return $this->userService->currentUser($request);
    }

    /**
     * Update a city.
     *
     * @OA\Post(
     *     path="/user/information",
     *     summary="بروزرسانی اطلاعات کاربر",
     *     description="",
     *     tags={"کاربر"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"_method","first_name","last_name","birthdate"},
     *                 @OA\Property(
     *                     property="_method",
     *                     type="string",
     *                     default="put",
     *                     enum={"put"},
     *                     description="این مقدار باید بصورت ثابت شود",
     *                 ),
     *                 @OA\Property(
     *                     property="first_name",
     *                     type="string",
     *                     description="نام"
     *                 ),
     *                 @OA\Property(
     *                     property="last_name",
     *                     type="string",
     *                     description="نام خانوادگی"
     *                 ),
     *                 @OA\Property(
     *                     property="birthdate",
     *                     type="string",
     *                     description="تاریخ تولد بصورت فرمت: Y/m/d مثال: 1401/06/20"
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
     * @throws Exception
     */
    public function informationUser(Request $request)
    {
        return $this->userService->informationUser($request);
    }

    /**
     * Logout.
     *
     * @OA\Post(
     *     path="/logout",
     *     summary="خروج از حساب کاربر",
     *     description="",
     *     tags={"کاربر"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="حساب کاربر مسدود میباشد",
     *         @OA\JsonContent()
     *     )
     * )
     */
    public function logout(Request $request)
    {
        return $this->userService->logout($request);
    }

    /**
     * Request login with mobile.
     *
     * @OA\Post(
     *     path="/login",
     *     summary="درخواست ورود کاربر",
     *     description="اگر hasPassword true باشد یعنی باید فرم ورود با کلمه عبور نمایش داده شود وگرنه کد فعالسازی به شماره کاربر ارسال خواهد شد",
     *     tags={"کاربر"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="mobile",
     *                     type="string",
     *                 ),
     *                 example={"mobile": "+989123456789"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="شماره موبایل معتبر نمیباشد",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="حساب کاربر مسدود میباشد",
     *         @OA\JsonContent()
     *     )
     * )
     * @throws UserAccountIsInactiveException
     */
    public function login(Request $request)
    {
        return $this->userService->login($request);
    }

    /**
     * Login user with mobile and password.
     *
     * @OA\Post(
     *     path="/login/password",
     *     summary="ورود به حساب کاربری با شماره موبایل و کلمه عبور",
     *     description="",
     *     tags={"کاربر"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="mobile",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                 ),
     *                 example={"mobile": "+989123456789","password": "1234"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="شماره موبایل یا کلمه عبور معتبر نمیباشد",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="حساب کاربر مسدود میباشد",
     *         @OA\JsonContent()
     *     )
     * )
     * @throws UserAccountIsInactiveException
     */
    public function loginPassword(Request $request)
    {
        return $this->userService->loginPassword($request);
    }

    /**
     * Confirm verification code.
     *
     * @OA\Post(
     *     path="/login/confirm",
     *     summary="اعتبار سنجی کد فعالسازی",
     *     description="",
     *     tags={"کاربر"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="mobile",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="verification_code",
     *                     type="string",
     *                 ),
     *                 example={"mobile": "+989123456789","verification_code": "123456"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="شماره موبایل یا کلمه عبور معتبر نمیباشد",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="حساب کاربر مسدود میباشد",
     *         @OA\JsonContent()
     *     )
     * )
     * @throws UserAccountIsInactiveException
     */
    public function loginConfirm(Request $request)
    {
        return $this->userService->loginConfirm($request);
    }

    /**
     * Resend verification code to mobile.
     *
     * @OA\Post(
     *     path="/login/otp/resend",
     *     summary="درخواست مجدد کد فعالسازی",
     *     description="",
     *     tags={"کاربر"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="mobile",
     *                     type="string",
     *                 ),
     *                 example={"mobile": "+989123456789"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="شماره موبایل معتبر نمیباشد",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="حساب کاربر مسدود میباشد",
     *         @OA\JsonContent()
     *     )
     * )
     * @throws UserAccountIsInactiveException
     */
    public function loginOtpResend(Request $request)
    {
        return $this->userService->loginOtpResend($request);
    }

    /**
     * Store a user.
     *
     * @OA\Post(
     *     path="/users",
     *     summary="ثبت کاربر",
     *     description="",
     *     tags={"کاربر"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"mobile"},
     *                 @OA\Property(
     *                     property="first_name",
     *                     type="string",
     *                     description="نام"
     *                 ),
     *                 @OA\Property(
     *                     property="last_name",
     *                     type="string",
     *                     description="نام خانوادگی"
     *                 ),
     *                 @OA\Property(
     *                     property="father_name",
     *                     type="string",
     *                     description="نام پدر"
     *                 ),
     *                 @OA\Property(
     *                     property="mobile",
     *                     type="string",
     *                     description="شماره موبایل"
     *                 ),
     *                 @OA\Property(
     *                     property="national_code",
     *                     type="number",
     *                     description="کد ملی"
     *                 ),
     *                 @OA\Property(
     *                     property="birthdate",
     *                     type="string",
     *                     description="تاریخ تولد بصورت شمسی با فرمت Y/m/d - مثال:1401/05/10"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     description="کلمه عبور"
     *                 ),
     *                 @OA\Property(
     *                     property="background",
     *                     type="string",
     *                     description="پس زمینه"
     *                 ),
     *                 @OA\Property(
     *                     property="color",
     *                     type="string",
     *                     description="رنگ"
     *                 ),
     *                 @OA\Property(
     *                     property="city_id",
     *                     type="number",
     *                     description="شناسه شهر کاربر"
     *                 ),
     *                 @OA\Property(
     *                     property="grade_id",
     *                     type="number",
     *                     description="شناسه مقطع تحصیلی کاربر"
     *                 ),
     *                 @OA\Property(
     *                     property="birth_place_id",
     *                     type="number",
     *                     description="شناسه شهر محل تولد کاربر"
     *                 ),
     *                 @OA\Property(
     *                     property="status",
     *                     type="string",
     *                     enum={"Active","Inactive"},
     *                     description="Active: فعال - Inactive: غیرفعال",
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
        return $this->userService->store($request);
    }

    /**
     * @OA\Post(
     *     path="/users/{id}/assign-role",
     *     summary="ثبت نقش کاربر",
     *     description="",
     *     tags={"کاربر"},
     *     @OA\Parameter(
     *         description="شناسه کاربر",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"role"},
     *                 @OA\Property(
     *                     property="role",
     *                     type="string",
     *                     description="نام نقش کاربری (انگلیسی)"
     *                 )
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
    public function assignRole(Request $request,$user)
    {
        return $this->userService->assignRole($request,$user);
    }
}
