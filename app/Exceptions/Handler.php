<?php

namespace App\Exceptions;


use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
            return $this->convertValidationExceptionToResponse($exception, $request);
        }
        if ($exception instanceof ModelNotFoundException) {
            $model = strtolower(class_basename($exception->getModel()));
            return response()->json(["error" => "{$model} with the specified id does not exist", "code" => 404], 404);
        }
        if ($exception instanceof MethodNotAllowedHttpException) {
            return response("The specified method for the request is invalid", 405);
        }
        if ($exception instanceof NotFoundHttpException) {
            return response("The specified URL does not exist", 404);
        }
        if ($exception instanceof HttpException) {
            return response($exception->getMessage(), $exception->getStatusCode());
        }
        if ($exception instanceof QueryException) {
            $errorCode = $exception->errorInfo[1];
            if ($errorCode == 1451) {
                return response("Sorry, you cannot delete this resource permanently because it is related to other resources", 409);
            }
        }
        return parent::render($request, $exception);
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        // if ($e->response) {
        $errors = $e->validator->errors()->getMessages();
        return response()->json($errors);
            //  $e->response;
        // }

        // return $request->expectsJson()
        //     ? $this->invalidJson($request, $e)
        //     : $this->invalid($request, $e);
    }
}
