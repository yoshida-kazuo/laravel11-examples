<?php

namespace App\Http\Requests\V1\Web\Guest\Env;

use App\Http\Requests\V1\Web\AjaxRequest;

class TimezoneRequest extends AjaxRequest
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
            //
        ];
    }

}
