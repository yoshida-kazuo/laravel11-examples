<?php

namespace App\Http\Requests\V1\Api;

use App\Exceptions\ApiValidationException;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Precognition;

class ApiRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return boolean
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  Validator $validator
     * @return void
     * @throws ApiValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $exception = ApiValidationException::class;

        throw (new $exception($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
