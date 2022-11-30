<?php

namespace App\Http\Controllers\V1\Api\User;

use App\Exceptions\User\UserAccountIsInactiveException;
use App\Http\Controllers\V1\Api\ApiBaseController;
use App\Services\V1\User\UserService;
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
     *                 required={"mobile","status",},
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
}
