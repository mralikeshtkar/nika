<?php

namespace App\Responses\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\Response as ResponseHttp;

class ApiResponse
{

    #region Constance

    /**
     * Status request status.
     *
     * @var bool
     */
    private bool $_status = true;

    /**
     * Check request has error or not.
     *
     * @var bool
     */
    private bool $_has_error = false;

    /**
     * Request message.
     *
     * @var string
     */
    private string $_message;

    /**
     * Request status code.
     * Default is: 200 [Success]
     *
     * @var int
     */
    private int $_code = ResponseHttp::HTTP_OK;

    /**
     * Request data.
     *
     * @var array
     */
    private array $_data = [];

    /**
     * Request errors.
     *
     * @var array
     */
    private array $_errors = [];

    /**
     * Validation data.
     *
     * @var array
     */
    private array $_validation_data;

    /**
     * Validation rules.
     *
     * @var array
     */
    private array $_validation_rules;

    /**
     * Validation messages.
     *
     * @var array
     */
    private array $_validation_messages = [];

    /**
     * Validation attributes.
     *
     * @var array
     */
    private array $_validation_attributes = [];

    #endregion

    #region Getters

    /**
     * Get request status.
     *
     * @return bool
     */
    public function getStatus(): bool
    {
        return $this->_status;
    }

    /**
     * Get request has error or not.
     *
     * @return bool
     */
    public function getHasError(): bool
    {
        return $this->_has_error;
    }

    /**
     * Get message request.
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->_message;
    }

    /**
     * Get request errors.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->_errors;
    }

    /**
     * Set request status code.
     *
     * @return int
     */
    public function getCode(): int
    {
        return $this->_code;
    }

    /**
     * Get request uri.
     *
     * @return string
     */
    public function getUri(): string
    {
        return Route::getCurrentRequest()->getRequestUri();
    }

    /**
     * Get request base.
     *
     * @return string
     */
    public function getBase(): string
    {
        return url('/');
    }

    /**
     * Get request data.
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->_data;
    }

    /**
     * Get validation data.
     *
     * @return array
     */
    public function getValidationData(): array
    {
        return $this->_validation_data;
    }

    /**
     * Get validation rules.
     *
     * @return array
     */
    public function getValidationRules(): array
    {
        return $this->_validation_rules;
    }

    /**
     * Get validation messages;
     *
     * @return array
     */
    public function getValidationMessages(): array
    {
        return $this->_validation_messages;
    }

    /**
     * Get validation attributes.
     *
     * @return array
     */
    public function getValidationAttributes(): array
    {
        return $this->_validation_attributes;
    }

    #endregion

    #region Setters

    /**
     * Set request status.
     *
     * @param bool $status
     * @return $this
     */
    public function setStatus(bool $status): static
    {
        $this->_status = $status;
        return $this;
    }

    /**
     * Set request has error or not.
     *
     * @param bool $has_error
     * @return $this
     */
    public function setHasError(bool $has_error): static
    {
        $this->_has_error = $has_error;
        return $this;
    }

    /**
     * Set message request.
     *
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message): static
    {
        $this->_message = $message;
        return $this;
    }

    /**
     * Set request errors.
     *
     * @param array $errors
     * @return $this
     */
    public function setErrors(array $errors): static
    {
        $this->_errors = $errors;
        return $this;
    }

    /**
     * Get request status code.
     *
     * @param int $code
     * @return $this
     */
    public function setCode(int $code): static
    {
        $this->_code = $code;
        return $this;
    }

    /**
     * Set request data.
     *
     * @param array $data
     * @return $this
     */
    public function setData(array $data): static
    {
        $this->_data = $data;
        return $this;
    }

    /**
     * Set validation data.
     *
     * @param array $validation_data
     * @return $this
     */
    public function setValidationData(array $validation_data): static
    {
        $this->_validation_data = $validation_data;
        return $this;
    }

    /**
     * Set validation rules.
     *
     * @param array $validation_rules
     * @return $this
     */
    public function setValidationRules(array $validation_rules): static
    {
        $this->_validation_rules = $validation_rules;
        return $this;
    }

    /**
     * Set validation messages;
     *
     * @param array $validation_messages
     * @return $this
     */
    public function setValidationMessages(array $validation_messages): static
    {
        $this->_validation_messages = $validation_messages;
        return $this;
    }

    /**
     * Set validation attributes.
     *
     * @param array $validation_attributes
     * @return $this
     */
    public function setValidationAttributes(array $validation_attributes): static
    {
        $this->_validation_attributes = $validation_attributes;
        return $this;
    }

    #endregion

    #region Methods

    /**
     * Initialize class.
     *
     * @return ApiResponse
     */
    #[Pure] private static function init(): ApiResponse
    {
        return new self();
    }

    /**
     * Initialize message.
     *
     * @param string $message
     * @param int $code
     * @return ApiResponse
     */
    public static function message(string $message, int $code = ResponseHttp::HTTP_OK): ApiResponse
    {
        return self::init()->setMessage($message)
            ->setCode($code);
    }

    /**
     * Initialize error.
     *
     * @param string $message
     * @param int $code
     * @return ApiResponse
     */
    public static function error(string $message, int $code = ResponseHttp::HTTP_INTERNAL_SERVER_ERROR): ApiResponse
    {
        return self::init()->setMessage($message)
            ->setCode($code)
            ->hasError();
    }

    /**
     * Initialize validation data.
     *
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param array $attributes
     * @return void
     */
    public static function validate(array $data, array $rules, array $messages = [], array $attributes = [])
    {
        self::init()
            ->setValidationData($data)
            ->setValidationRules($rules)
            ->setValidationMessages($messages)
            ->setValidationAttributes($attributes)
            ->validation();
    }

    /**
     * Authorize request.
     *
     * @param bool $access
     * @return void
     */
    public static function authorize(bool $access)
    {
        abort_if(!$access, self::init()
            ->setMessage(trans("This action is unauthorized"))
            ->setCode(ResponseHttp::HTTP_FORBIDDEN)
            ->hasError()
            ->send());
    }

    /**
     * Send a json response;
     *
     * @return JsonResponse
     */
    public function send(): JsonResponse
    {
        return Response::json([
            'status' => $this->getStatus(),
            'has_error' => $this->getHasError(),
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
            'base' => $this->getBase(),
            'uri' => $this->getUri(),
            'data' => $this->getData(),
            'errors' => $this->getErrors(),
        ], $this->getCode());
    }

    /**
     * Set request has error.
     *
     * @return $this
     */
    public function hasError(): static
    {
        $this->setHasError(true);
        return $this;
    }

    /**
     * Add element to request data.
     *
     * @param $key
     * @param $value
     * @return $this
     */
    public function addData($key, $value): static
    {
        $this->_data[$key] = $value;
        return $this;
    }

    /**
     * Add element to request errors.
     *
     * @param $key
     * @param $value
     * @return $this
     */
    public function addError($key, $value): static
    {
        $this->_errors[$key] = $value;
        return $this;
    }

    /**
     * @return void
     */
    private function validation()
    {
        $validator = Validator::make($this->getValidationData(), $this->getValidationRules(), $this->getValidationMessages(), $this->getValidationAttributes());
        abort_if($validator->fails(), $this->setMessage(trans("The given data was invalid"))
            ->setCode(ResponseHttp::HTTP_UNPROCESSABLE_ENTITY)
            ->setErrors($validator->errors()->toArray())
            ->hasError()
            ->send());
    }

    #endregion

}
