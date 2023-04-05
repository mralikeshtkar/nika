<?php

namespace App\Services\V1\Rahjoo;

use App\Enums\Rahjoo\RahjooSupportStep;
use App\Http\Resources\V1\Rahjoo\RahjooSupportResource;
use App\Repositories\V1\Rahjoo\Interfaces\RahjooSupportRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RahjooSupportService extends BaseService
{
    private RahjooSupportRepositoryInterface $rahjooSupportRepository;

    #region Constructor

    /**
     * RahjooService constructor.
     *
     * @param RahjooSupportRepositoryInterface $rahjooSupportRepository
     */
    public function __construct(RahjooSupportRepositoryInterface $rahjooSupportRepository)
    {
        $this->rahjooSupportRepository = $rahjooSupportRepository;
    }

    #endregion

    #region Public methods

    /**
     * @param Request $request
     * @param $rahjooSupport
     * @return JsonResponse
     */
    public function show(Request $request, $rahjooSupport): JsonResponse
    {
        $rahjooSupport = $this->rahjooSupportRepository->with(['support:id,first_name,last_name'])
            ->findOrFailById($rahjooSupport);
        ApiResponse::authorize($request->user()->can('show', $rahjooSupport));
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('rahjooSupport', new RahjooSupportResource($rahjooSupport))
            ->send();
    }

    /**
     * @param Request $request
     * @param $rahjooSupport
     * @return JsonResponse
     */
    public function update(Request $request, $rahjooSupport): JsonResponse
    {
        $rahjooSupport = $this->rahjooSupportRepository->notCanceled()
            ->with(['support:id,first_name,last_name'])
            ->findOrFailById($rahjooSupport);
        ApiResponse::authorize($request->user()->can('update', $rahjooSupport));
        ApiResponse::validate($request->all(), [
            'step' => ['required', new EnumValue(RahjooSupportStep::class)],
        ]);
        $this->rahjooSupportRepository->update($rahjooSupport, [
            'step' => $request->step,
        ]);
        return ApiResponse::message(trans("The :attribute was successfully updated", ['attribute' => trans('RahjooSupport')]))
            ->send();
    }

    /**
     * @param Request $request
     * @param $rahjooSupport
     * @return JsonResponse
     */
    public function cancel(Request $request, $rahjooSupport): JsonResponse
    {
        $rahjooSupport = $this->rahjooSupportRepository->notCanceled()
            ->with(['support:id,first_name,last_name'])
            ->findOrFailById($rahjooSupport);
        ApiResponse::authorize($request->user()->can('cancel', $rahjooSupport));
        ApiResponse::validate($request->all(), [
            'description' => ['nullable', 'string'],
        ]);
        $this->rahjooSupportRepository->update($rahjooSupport, [
            'cancel_description' => $request->description,
            'canceled_at' => now(),
        ]);
        return ApiResponse::message(trans("Mission accomplished"))->send();
    }

    /**
     * @param Request $request
     * @param $rahjooSupport
     * @return JsonResponse
     */
    public function changeStep(Request $request, $rahjooSupport): JsonResponse
    {
        $rahjooSupport = $this->rahjooSupportRepository->notCanceled()
            ->findOrFailById($rahjooSupport);
        ApiResponse::authorize($request->user()->can('chaneStep', $rahjooSupport));
        ApiResponse::validate($request->all(), [
            'step' => ['required', new EnumValue(RahjooSupportStep::class)],
        ]);
        $this->rahjooSupportRepository->update($rahjooSupport, [
            'step' => $request->step,
        ]);
        return ApiResponse::message(trans("Mission accomplished"))->send();
    }

    public function generatePayUrl(Request $request, $rahjooSupport)
    {
        $rahjooSupport = $this->rahjooSupportRepository->notCanceled()
            ->findOrFailById($rahjooSupport);
        dd("ok");
    }

    #endregion

}
