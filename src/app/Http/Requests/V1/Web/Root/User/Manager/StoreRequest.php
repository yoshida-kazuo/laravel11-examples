<?php

namespace App\Http\Requests\V1\Web\Root\User\Manager;

use App\Http\Requests\V1\Web\Auth\RegisterRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends RegisterRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return boolean
     */
    public function authorize(): bool
    {
        return parent::authorize();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'role_id' => [
                'required',
                'exists:App\Models\Role,id'
            ],
        ]);
    }

}
