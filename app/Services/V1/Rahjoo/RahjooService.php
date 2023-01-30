<?php

namespace App\Services\V1\Rahjoo;

use App\Enums\Role;
use App\Http\Resources\V1\PaginationResource;
use App\Http\Resources\V1\Rahjoo\RahjooResource;
use App\Models\Package;
use App\Models\Rahjoo;
use App\Models\User;
use App\Repositories\V1\Package\Interfaces\PackageRepositoryInterface;
use App\Repositories\V1\Rahjoo\Interfaces\RahjooRepositoryInterface;
use App\Repositories\V1\User\Interfaces\UserRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Rules\UserHasRoleRule;
use App\Services\V1\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

class RahjooService extends BaseService
{
    private RahjooRepositoryInterface $rahjooRepository;

    #region Constructor

    /**
     * RahjooService constructor.
     *
     * @param RahjooRepositoryInterface $rahjooRepository
     */
    public function __construct(RahjooRepositoryInterface $rahjooRepository)
    {
        $this->rahjooRepository = $rahjooRepository;
    }

    #endregion

    #region Public methods

    /**
     * Get rahjoos with user,mother and father as pagination.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('index', Rahjoo::class));
        $rahjoos = $this->rahjooRepository->with(['user'])
            ->paginate($request->get('perPage', 10));
        $resource = PaginationResource::make($rahjoos)->additional(['itemsResource' => RahjooResource::class]);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('rahjoos', $resource)
            ->send();
    }

    /**
     * Show a rahjoo.
     *
     * @param Request $request
     * @param $rahjoo
     * @return JsonResponse
     */
    public function show(Request $request, $rahjoo): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('show', Rahjoo::class));
        $rahjoo = $this->rahjooRepository->with(['user', 'father', 'mother'])->findorFailById($rahjoo);
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('rahjoos', RahjooResource::make($rahjoo))
            ->send();
    }

    /**
     * Update or create rahjoo information.
     *
     * @param Request $request
     * @param $user
     * @return JsonResponse
     */
    public function store(Request $request, $user): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('create', Rahjoo::class));
        $user = resolve(UserRepositoryInterface::class)->findOrFailById($user);
        abort_if(!$user->isRahjoo(), ApiResponse::error(trans("The user is not a rahjoo"), Response::HTTP_BAD_REQUEST)->send());
        ApiResponse::validate($request->all(), [
            'agent_id' => ['nullable', new UserHasRoleRule(Role::AGENT)],
            'school' => ['nullable', 'string'],
            'which_child_of_family' => ['nullable', 'numeric', 'min:1'],
        ], [], [
            'agent_id' => trans('Agent'),
        ]);
        $rahjoo = $this->rahjooRepository->updateOrCreate([
            'user_id' => $user->id,
        ], [
            'user_id' => $user->id,
            'agent_id' => $request->agent_id,
            'school' => $request->school,
            'which_child_of_family' => $request->which_child_of_family,
            'disease_background' => $request->disease_background,
        ]);
        return ApiResponse::message(trans("The information was register successfully"))
            ->addData('rahjoo', RahjooResource::make($rahjoo))
            ->send();
    }

    /**
     * @param Request $request
     * @param $rahjoo
     * @return JsonResponse
     */
    public function assignPackage(Request $request, $rahjoo): JsonResponse
    {
        $rahjoo = $this->rahjooRepository->with(['user', 'father', 'mother'])->findorFailById($rahjoo);
        ApiResponse::validate($request->all(), [
            'package_id' => ['required', 'exists:' . Package::class . ',id'],
        ]);
        /** @var Package $package */
        $package = resolve(PackageRepositoryInterface::class)
            ->select(['id', 'status'])
            ->findOrFailById($request->package_id);
        abort_if($package->isInactive(), ApiResponse::error(trans("Package is inactive"), Response::HTTP_BAD_REQUEST)->send());
        $this->rahjooRepository->updatePackage($rahjoo,$package->id);
        return ApiResponse::message(trans("Mission accomplished"))->send();
    }

    /**
     * Destroy a rahjoo.
     *
     * @param Request $request
     * @param $rahjoo
     * @return JsonResponse
     */
    public function destroy(Request $request, $rahjoo): JsonResponse
    {
        ApiResponse::authorize($request->user()->can('delete', Rahjoo::class));
        $rahjoo = $this->rahjooRepository->findOrFailById($rahjoo);
        $this->rahjooRepository->destroy($rahjoo);
        return ApiResponse::message(trans("The :attribute was successfully deleted", ['attribute' => trans('Rahjoo')]))->send();
    }

    #endregion

}
