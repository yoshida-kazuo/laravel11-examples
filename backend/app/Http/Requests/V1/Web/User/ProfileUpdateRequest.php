<?php

namespace App\Http\Requests\V1\Web\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
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
                'string',
                'max:255',
            ],
            'email' => [
                'email',
                'max:255',
                Rule::unique(User::class)
                    ->ignore($this->user()->id)
            ],
        ];
    }

}
