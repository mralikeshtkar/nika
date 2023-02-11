<?php

namespace App\Services\V1\User;

use App\Enums\Media\MediaExtension;
use App\Enums\Role as RoleEnum;
use App\Enums\User\UserBackground;
use App\Enums\User\UserColor;
use App\Enums\UserStatus;
use App\Exceptions\User\UserAccountIsInactiveException;
use App\Http\Resources\V1\PaginationResource;
use App\Http\Resources\V1\User\SingleUserResource;
use App\Http\Resources\V1\User\UserResource;
use App\Models\City;
use App\Models\Grade;
use App\Models\Intelligence;
use App\Models\RahjooParent;
use App\Models\Role;
use App\Models\User;
use App\Repositories\V1\Media\Interfaces\MediaRepositoryInterface;
use App\Repositories\V1\User\Interfaces\UserRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Rules\MobileIsUniqueRule;
use App\Rules\MobileRule;
use App\Rules\NationalCodeRule;
use App\Rules\PasswordRule;
use App\Services\V1\BaseService;
use BenSampo\Enum\Rules\EnumKey;
use BenSampo\Enum\Rules\EnumValue;
use Exception;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserService extends BaseService
{
    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;

    #region Constructor

    /**
     * UserService constructor.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #endregion

    #region Public methods

    public function index(Request $request): JsonResponse
    {
        $users = $this->userRepository
            ->filterPagination($request)
            ->paginate($request->get('perPage', 10));
        $resource = PaginationResource::make($users)->additional(['itemsResource' => UserResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('users', $resource)
            ->send();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function onlyRahjoos(Request $request): JsonResponse
    {
        $users = $this->userRepository
            ->select(['id','first_name','last_name','mobile'])
            ->hasRole(RoleEnum::RAHJOO)
            ->searchName($request)
            ->searchMobile($request)
            ->searchNotionalCode($request)
            ->paginate($request->get('perPage', 10));
        $resource = PaginationResource::make($users)->additional(['itemsResource' => UserResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('users', $resource)
            ->send();
    }

    /**
     * @param Request $request
     * @param $user
     * @return JsonResponse
     */
    public function storeRahnamaIntelligences(Request $request, $user): JsonResponse
    {
        $user = $this->userRepository
            ->hasRole(RoleEnum::RAHNAMA)
            ->findOrFailById($user);
        ApiResponse::validate($request->all(), [
            'intelligences' => ['required', 'array', 'min:1'],
            'intelligences.*' => ['required', 'numeric', 'exists:' . Intelligence::class . ',id'],
        ]);
        $this->userRepository->storeRahnamaIntelligences($request, $user, $request->intelligences);
        return ApiResponse::message(trans("The information was received successfully"))
            ->send();
    }

    /**
     * @param Request $request
     * @param $user
     * @return JsonResponse
     */
    public function rahnama(Request $request, $user): JsonResponse
    {
        $user = $this->userRepository
            ->hasRole(RoleEnum::RAHNAMA)
            ->with(['rahnamaIntelligences:id,title'])
            ->findOrFailById($user);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('user', new UserResource($user))
            ->send();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function onlyRahnama(Request $request): JsonResponse
    {
        $users = $this->userRepository
            ->select(['id','first_name','last_name','mobile'])
            ->with(['rahnamaIntelligences:id,title'])
            ->hasRole(RoleEnum::RAHNAMA)
            ->searchName($request)
            ->withRahnamaRahjoosCount()
            ->searchMobile($request)
            ->searchNotionalCode($request)
            ->paginate($request->get('perPage', 10));
        $resource = PaginationResource::make($users)->additional(['itemsResource' => UserResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('users', $resource)
            ->send();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function onlyRahyab(Request $request): JsonResponse
    {
        $users = $this->userRepository
            ->select(['id','first_name','last_name','mobile'])
            ->hasRole(RoleEnum::RAHYAB)
            ->searchName($request)
            ->withRahyabRahjoosCount()
            ->searchMobile($request)
            ->searchNotionalCode($request)
            ->paginate($request->get('perPage', 10));
        $resource = PaginationResource::make($users)->additional(['itemsResource' => UserResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('users', $resource)
            ->send();
    }

    /**
     * @param Request $request
     * @param $user
     * @return JsonResponse
     */
    public function uploadProfile(Request $request, $user): JsonResponse
    {
        $user = $this->userRepository->select(['id'])
            ->with(['profile'])
            ->findOrFailById($user);
        ApiResponse::validate($request->all(), [
            'file' => ['required', ['required', 'file', 'mimes:' . implode(",", MediaExtension::getExtensions(MediaExtension::Image))]]
        ]);
        if ($user->profile) resolve(MediaRepositoryInterface::class)->destroy($user->profile);
        $this->userRepository->uploadProfile($user, $request->file);
        $user = $this->userRepository->select(['id'])
            ->with(['profile'])
            ->findOrFailById($user);
        return ApiResponse::message(trans("Mission accomplished"))
            ->addData('user', new UserResource($user))
            ->send();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function currentUser(Request $request): JsonResponse
    {
        $user = $request->user()->load('rahjoo')
            ->only(['id', 'background', 'color', 'first_name', 'last_name', 'mobile', 'birthdate', 'rahjoo']);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('user', new SingleUserResource($user))
            ->send();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function informationUser(Request $request): JsonResponse
    {
        ApiResponse::validate($request->all(), [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'birthdate' => ['required', 'jdate:' . User::BIRTHDATE_VALIDATION_FORMAT],
        ]);
        $user = $this->userRepository->update($request->user(), [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birthdate' => Verta::parseFormat(User::BIRTHDATE_VALIDATION_FORMAT, $request->birthdate)->datetime(),
        ]);
        $user = $user->only(['id', 'first_name', 'last_name', 'mobile', 'birthdate']);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('user', new SingleUserResource($user))
            ->send();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $this->userRepository->logout($request->user());
        return ApiResponse::message(trans("Mission accomplished"))->send();
    }

    /**
     * Request to log in with mobile.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws UserAccountIsInactiveException
     */
    public function login(Request $request): JsonResponse
    {
        ApiResponse::validate($request->all(), [
            'mobile' => ['required', new MobileRule()],
        ]);
        $request->merge(['mobile' => to_valid_mobile_number($request->mobile)]);
        $user = $this->userRepository->firstOrCreateByMobile($request->mobile);
        $this->_checkUserAccountIsNotInactive($user);
        if ($user->hasPassword()) {
            return ApiResponse::message(trans("The information was received successfully"))
                ->addData('mobile', $user->mobile)
                ->addData('hasPassword', $user->hasPassword())
                ->send();
        } else {
            $code = $this->_generateVerificationCode($user);
            return ApiResponse::message(trans("Activation code sent successfully"))
                ->addData('mobile', $user->mobile)
                ->addData('code', $code)
                ->addData('hasPassword', $user->hasPassword())
                ->send();
        }
    }

    /**
     * Login user with user and password
     *
     * @throws UserAccountIsInactiveException
     */
    public function loginPassword(Request $request): JsonResponse
    {
        ApiResponse::validate($request->all(), [
            'mobile' => ['required', new MobileRule()],
            'password' => ['required', /*new PasswordRule()*/],
        ]);
        $request->merge(['mobile' => to_valid_mobile_number($request->mobile)]);
        $user = $this->userRepository->verified()->findByMobile($request->mobile);
        $this->_checkUserAccountIsNotInactive($user);
        if ($user && Hash::check($request->password, $user->password)) {
            $this->userRepository->markAsVerified($user);
            $token = $user->generateToken();
            return ApiResponse::message(trans("Login was successful"))
                ->addData('token', $token)
                ->send();
        } else {
            return ApiResponse::error(trans("Mobile or password is invalid"), Response::HTTP_BAD_REQUEST)->send();
        }
    }

    /**
     * @throws UserAccountIsInactiveException
     */
    public function loginConfirm(Request $request): JsonResponse
    {
        //todo verification code length
        ApiResponse::validate($request->all(), [
            'mobile' => ['required', new MobileRule()],
            'verification_code' => ['required', 'numeric'],
        ]);
        $request->merge(['mobile' => to_valid_mobile_number($request->mobile)]);
        /** @var User $user */
        $user = $this->userRepository->findByMobile($request->mobile);

        if (!$user) return ApiResponse::error(trans("User is invalid"), Response::HTTP_BAD_REQUEST)
            ->send();

        $this->_checkUserAccountIsNotInactive($user);

        if (!$user->checkVerificationCode($request->verification_code))
            return ApiResponse::error(trans("Verification code is invalid"), Response::HTTP_BAD_REQUEST)
                ->send();

        if ($user->verificationCodeIsExpired())
            return ApiResponse::error(trans("Verification code is expired"), Response::HTTP_BAD_REQUEST)
                ->send();

        $this->userRepository->markAsVerified($user);
        $token = $user->generateToken();
        return ApiResponse::message(trans("Login was successful"))
            ->addData('token', $token)
            ->addData('hasName', !is_null($user->first_name))
            ->addData('isPersonnel', $user->isPersonnel())
            ->addData('role', optional($user->roles()->first())->name)
            ->send();
    }

    /**
     * Resend verification code.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws UserAccountIsInactiveException
     */
    public function loginOtpResend(Request $request): JsonResponse
    {
        ApiResponse::validate($request->all(), [
            'mobile' => ['required', new MobileRule()]
        ]);
        $request->merge(['mobile' => to_valid_mobile_number($request->mobile)]);
        $user = $this->userRepository->firstOrCreateByMobile($request->mobile);
        $this->_checkUserAccountIsNotInactive($user);
        $code = $this->_generateVerificationCode($user);
        return ApiResponse::message(trans("Activation code sent successfully"))
            ->addData('mobile', $user->mobile)
            ->addData('code', $code)
            ->send();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('create', User::class));
        ApiResponse::validate($request->all(), [
            'background' => ['nullable', new EnumValue(UserBackground::class)],
            'color' => ['nullable', new EnumValue(UserColor::class)],
            'first_name' => ['nullable', 'string'],
            'last_name' => ['nullable', 'string'],
            'father_name' => ['nullable', 'string'],
            'mobile' => ['required', new MobileRule()],
            'national_code' => ['nullable', new NationalCodeRule()],
            'birthdate' => ['nullable', 'jdate:' . User::BIRTHDATE_VALIDATION_FORMAT],
            'password' => ['nullable', new PasswordRule()],
            'status' => ['nullable', new EnumKey(UserStatus::class)],
            'city_id' => ['nullable', 'exists:' . City::class . ',id'],
            'grade_id' => ['nullable', 'exists:' . Grade::class . ',id'],
            'birth_place_id' => ['nullable', 'exists:' . City::class . ',id'],
        ]);
        $request->merge([
            'mobile' => to_valid_mobile_number($request->mobile),
            'birthdate' => $request->filled('birthdate') ? Verta::parseFormat(User::BIRTHDATE_VALIDATION_FORMAT, $request->birthdate) : null,
            'password' => $request->filled('password') ? Hash::make($request->password) : null,
            'status' => $request->filled('status') ? UserStatus::getValue($request->status) : null,
        ]);
        $user = $this->userRepository->updateOrCreate([
            'mobile' => $request->mobile,
        ], collect([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'father_name' => $request->father_name,
            'mobile' => $request->mobile,
            'national_code' => $request->national_code,
            'ip' => $request->ip(),
            'birthdate' => $request->filled('birthdate') ? $request->birthdate->datetime() : null,
            'password' => $request->password,
            'verified_at' => now(),
            'status' => $request->status,
            'city_id' => $request->city_id,
            'grade_id' => $request->grade_id,
            'birth_place_id' => $request->birth_place_id,
        ])->when($request->filled('status'), function (Collection $collection) use ($request) {
            $collection->put('status', $request->status);
        }, function (Collection $collection) {
            $collection->put('status', UserStatus::Active);
        })->when($request->filled('background'), function (Collection $collection) use ($request) {
            $collection->put('background', $request->background);
        })->when($request->filled('color'), function (Collection $collection) use ($request) {
            $collection->put('color', $request->color);
        })->toArray());
        return ApiResponse::message(trans("The :attribute was successfully registered", ['attribute' => trans('User')]), Response::HTTP_CREATED)
            ->addData('user', UserResource::make($user))
            ->send();
    }

    /**
     * @param Request $request
     * @param $user
     * @return JsonResponse
     */
    public function assignRole(Request $request, $user): JsonResponse
    {
        $user = $this->userRepository->select(['id'])->findOrFailById($user);
        ApiResponse::validate($request->all(), [
            'role' => ['required', 'exists:' . Role::class . ',name'],
        ]);
        $this->userRepository->assignRole($user, $request->role);
        return ApiResponse::message(trans("Mission accomplished"))
            ->send();
    }

    #endregion

    #region Private methods

    /**
     * Get last verification code.
     * Generate new verification code If verification code is expired.
     *
     * @param $user
     * @return int
     */
    private function _generateVerificationCode($user): int
    {
        return $user && $user->verification_code && !$user->verificationCodeIsExpired() ? $user->verification_code : $this->userRepository->updateVerificationCode($user, resolve(VerificationCodeService::class)->generate());
    }

    /**
     * Check user account is not inactive.
     *
     * @param $user
     * @return void
     * @throws UserAccountIsInactiveException
     */
    private function _checkUserAccountIsNotInactive($user)
    {
        if ($user && $user->isInactive())
            throw new UserAccountIsInactiveException(trans("Your account is inactive"), Response::HTTP_BAD_REQUEST);
    }

    #endregion

}
