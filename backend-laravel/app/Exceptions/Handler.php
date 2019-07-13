<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Exceptions \ {
AuthFailException, NotRegisterException, GeneralException, Zend_Mail_Exception
};
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Google_Service_Exception;
use Whoops\Exception\ErrorException;
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ValidationException) {
            $errors = $exception->validator->errors()->getMessages();
            return response()->json([
                'error' => [
                    'message'     => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
                    'status_code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                    'errors' => $errors,
                ],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($exception instanceof AuthFailException) {
            return response()->json([
                'error' => [
                    'message'     => route('google.auth'),
                    'status_code' => Response::HTTP_UNAUTHORIZED,
                    'errors' => 'Unauthorized',
                ],
            ], Response::HTTP_UNAUTHORIZED);
        }
        if ($exception instanceof NotRegisterException) {
            return response()->json([
                'error' => [
                    'message'     => route('google.checkAuth'),
                    'status_code' => Response::HTTP_UNAUTHORIZED,
                    'errors' => 'Not registered on server',
                ],
            ], Response::HTTP_UNAUTHORIZED);
        }
        if ($exception instanceof GeneralException) {
            return response()->json([
                'error' => [
                    'message'     => $exception->errorMessage,
                    'status_code' => $exception->status,
                    'errors' => $exception->error,
                ],
            ], $exception->status);
        }
        if ($exception instanceof Zend_Mail_Exception) {
            return response()->json([
                'error' => [
                    'message'     => $exception->errorMessage,
                    'status_code' => $exception->status,
                    'errors' => $exception->error,
                ],
            ], $exception->status);
        }
        if ($exception instanceof Google_Service_Exception) {
            return response()->json(
                $exception->getMessage()
            , $exception->getCode());
        }
        if ($exception instanceof IdentityProviderException) {
            return response()->json([
                $exception->getResponseBody(),
            ], Response::HTTP_UNAUTHORIZED);
        }

        return parent::render($request, $exception);
    }
}
