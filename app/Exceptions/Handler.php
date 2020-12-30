<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Auth\Access\AuthorizationException;

use Throwable;

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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            return response()->json(['status' => 'error', 'errors' => ['Model not found!']], config('constants.response_codes.not_found'));
        }

        if ($exception instanceof QueryException) {

            $exceptionErrorCode = 0;
            if(isset($exception->errorInfo[1])){
                $exceptionErrorCode = $exception->errorInfo[1];
            }

            switch ($exceptionErrorCode) {
                case 1048:
                    $errors = ['Database Error: Missing required fields.'];
                    break;
                case 1062:
                    $errors = ['Database Error: Duplicate entry error.'];
                    break;
                case 1364:
                    $errors = ['Database Error: No Record Found'];
                    break;
                default;
                    $errors = ['Problem executing database command!'];
                    break;
            }

            return response()->json(['status' => 'error', 'errors' => $errors, 'message' => $exception->getMessage()], config('constants.response_codes.database_error'));
        }

        if($exception instanceOf AuthorizationException) {
            return response()->json(['status' => 'error', 'errors' => ['This action is unauthorized']], config('constants.response_codes.not_found')); 
        }
        return parent::render($request, $exception);
    }
}
