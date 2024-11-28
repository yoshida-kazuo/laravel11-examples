<?php

namespace App\Http\Requests\V1\Web\Guest\Env;

use App\Http\Requests\V1\Web\AjaxRequest;
use Illuminate\Validation\Rule;

class LangUpdateRequest extends AjaxRequest
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
        return [
            'lang' => [
                'required',
                Rule::in(
                    array_flip(
                        config('locale')
                    )
                ),
            ],
        ];
    }

}
