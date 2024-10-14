<?php

namespace App\Exceptions;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AppAjaxException extends AppErrorException
{

    /**
     * エラーメッセージを格納
     *
     * @var array
     */
    protected $errors = [];

    /**
     * ステータスコードを格納
     *
     * @var integer
     */
    protected $statusCode = 400;

    /**
     * Create a new application error exception.
     *
     * @param string $message
     * @param integer $code
     */
    public function __construct(
        string $message,
        int $code = 400
    ) {
        $this->errors = json_decode($message, true);
        $this->statusCode = $code;

        parent::__construct(print_r($this->errors, true), $code);
    }

    /**
     * 例外をHTTPレスポンスとして返す
     *
     * @param Request $request
     * @return Response
     */
    public function render(Request $request): Response
    {
        return response(
            [
                'result'    => 'ng',
                'data'      => null,
                'errors'    => $this->errors,
            ],
            $this->statusCode
        );
    }

}
