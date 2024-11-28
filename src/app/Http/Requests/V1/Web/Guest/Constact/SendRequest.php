<?php

namespace App\Http\Requests\V1\Web\Guest\Constact;

use Illuminate\Foundation\Http\FormRequest;

class SendRequest extends FormRequest
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
            'name' => [
                'required',
                'max:255',
            ],
            'email' => [
                'required',
                'max:255',
                'email:rfc,dns',
            ],
            'message' => [
                'required',
                'max:1000',
            ],
        ];
    }

}
