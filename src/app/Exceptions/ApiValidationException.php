<?php

namespace App\Exceptions;

use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class ApioValidateException extends ValidationException
{

    /**
     * エラーメッセージを含むレスポンスデータを生成
     *
     * @param Request $request
     * @return array
     */
    public function responseData(Request $request): array
    {
        return [
            'result' => 'ng',
            'errors'    => Arr::map(
                $this->errors(),
                fn (array $value, string $key): string => reset($value)
            ),
        ];
    }

}
