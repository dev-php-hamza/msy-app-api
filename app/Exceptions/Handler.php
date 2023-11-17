<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;

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
      if ($exception instanceof ModelNotFoundException) {
        return response()->json(['errors' => 'Entry for '.str_replace('App\\', '', $exception->getModel()).' not found'], 404);
      }

      if ($exception instanceof MethodNotAllowedHttpException) {
        return response()->json(['errors' => 'Method you are trying to get is not found'], 404);
      }

      if ($exception instanceof QueryException) {
        $integrityConstraintViolation = 1451;
        if ($exception->errorInfo[1] == $integrityConstraintViolation ) {
          $message = 'Cannot proceed with query, it is referenced by other records in the database.';
        }else{
          $message = 'Could not execute query: '.$exception->errorInfo[2];
        }
        return response()->json(['errors'=> $message], 406);
      }

      if ($exception instanceof NotFoundHttpException) {
        return response()->json(['errors'=> "Url does not exist."]);
      }

      return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        
        return redirect()->guest(route('login'));
    }
}
