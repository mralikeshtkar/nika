<?php

namespace App\Services\V1\Rahjoo;

use App\Enums\Payment\PaymentStatus;
use App\Enums\Rahjoo\RahjooSupportStep;
use App\Events\Payment\PaymentWasSuccessful;
use App\Http\Resources\V1\Payment\PaymentResource;
use App\Http\Resources\V1\Rahjoo\RahjooSupportResource;
use App\Models\Order;
use App\Models\Package;
use App\Models\RahjooSupport;
use App\Repositories\V1\Package\Interfaces\PackageRepositoryInterface;
use App\Repositories\V1\Payment\Interfaces\PaymentRepositoryInterface;
use App\Repositories\V1\Rahjoo\Interfaces\RahjooSupportRepositoryInterface;
use App\Responses\Api\ApiResponse;
use App\Services\V1\BaseService;
use App\Services\V1\Payment\PaymentService;
use BenSampo\Enum\Rules\EnumValue;
use Exception;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Multipay\Invoice;
use Shetabit\Multipay\Receipt;
use Shetabit\Multipay\RedirectionForm;
use Shetabit\Payment\Facade\Payment;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

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
        //ApiResponse::authorize($request->user()->can('cancel', $rahjooSupport));
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

    public function informationCompleted(Request $request, $rahjooSupport)
    {
        /** @var RahjooSupport $rahjooSupport */
        $rahjooSupport = $this->rahjooSupportRepository->notCanceled()
            ->findOrFailById($rahjooSupport);
        $payment = $rahjooSupport->payments()
            ->latest()
            ->success()
            ->first();
        if (!$payment) {
            return ApiResponse::error(trans('Payment has not been made'))->send();
        }
        try {
            return DB::transaction(function () use ($request, $rahjooSupport, $payment) {
                $this->rahjooSupportRepository->update($rahjooSupport, [
                    'step' => RahjooSupportStep::Third,
                ]);
                $rahjooSupport->orders()->create([
                    'rahjoo_id' => $rahjooSupport->rahjoo_id,
                    'payment_id' => $payment->id,
                    'code' => IdGenerator::generate(['table' => (new Order())->getTable(), 'field' => 'code', 'length' => 6, 'prefix' => rand(1, 9)]),
                ]);
                return ApiResponse::message(trans("Mission accomplished"))->send();
            });
        } catch (Throwable $e) {
            return ApiResponse::error(trans('Internal server error'))->send();
        }

    }

    public function generatePayUrl(Request $request, $rahjooSupport)
    {
        $rahjooSupport = $this->rahjooSupportRepository->notCanceled()
            ->findOrFailById($rahjooSupport);
        ApiResponse::validate($request->all(), [
            'package_id' => ['required', 'exists:' . Package::class . ',id'],
        ]);
        /** @var Package $package */
        $package = resolve(PackageRepositoryInterface::class)->findOrFailById($request->package_id);
        try {
            return DB::transaction(function () use ($request, $rahjooSupport, $package) {
                $invoice = (new Invoice())->via(config('payment.default'))->amount($package->price);
                $payment = Payment::callbackUrl(route('rahjoo-support.verify-payment'))->purchase($invoice)->pay();
                $package->payments()->create([
                    'owner_id' => $request->user()->id,
                    'rahjoo_support_id' => $rahjooSupport->id,
                    'action' => $payment->getAction(),
                    'invoice_id' => $invoice->getTransactionId(),
                    'amount' => $package->price,
                    'gateway' => $invoice->getDriver(),
                ]);
                return ApiResponse::message(trans("Mission accomplished"))
                    ->addData('payment', $payment->getAction())
                    ->send();
            });
        } catch (Exception $e) {
            dd($e);
            return ApiResponse::error(trans('Internal server error'))->send();
        }
    }

    /**
     * @param Request $request
     * @param $rahjooSupport
     * @return JsonResponse
     */
    public function payments(Request $request, $rahjooSupport): JsonResponse
    {
        /** @var RahjooSupport $rahjooSupport */
        $rahjooSupport = $this->rahjooSupportRepository->notCanceled()
            ->findOrFailById($rahjooSupport);
        $payments = $rahjooSupport->payments()
            ->with('paymentable:id,title,price,description')
            ->when($request->filled('status') && in_array($request->status, PaymentStatus::asArray()), function ($q) use ($request) {
                $q->where('status', $request->status);
            })->latest()
            ->get();
        return ApiResponse::message(trans("The information was received successfully"))
            ->addData('payments', PaymentResource::collection($payments))
            ->send();
    }

    public function verifyPayment(Request $request)
    {
        $paymentRepository = resolve(PaymentRepositoryInterface::class);
        $payment = $paymentRepository->statusPending()
            ->findOrFailByInvoiceId($request->get('Authority'));
        try {
            return DB::transaction(function () use ($paymentRepository, $payment, $request) {
                $receipt = Payment::amount($payment->amount)->transactionId($payment->invoice_id)->verify();
                $paymentRepository->update($payment, [
                    'referenceId' => $receipt->getReferenceId(),
                    'date' => $receipt->getDate(),
                    'status' => $request->get('Status') == "OK" ? PaymentStatus::Success : PaymentStatus::Canceled,
                ]);
                event(new PaymentWasSuccessful($payment));
                return redirect('http://192.168.1.104:8000/rahjoo/dashboard');
            });
        } catch (InvalidPaymentException $e) {
            abort(Response::HTTP_NOT_FOUND);
        }
    }

    #endregion

}
