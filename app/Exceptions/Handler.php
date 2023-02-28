<?php

namespace App\Exceptions;

use Exception;
use Psr\Log\LoggerInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

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
     * 将日志的记录 chanel 改为daily
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function report(\Throwable $exception)
    {
        if ($this->shouldntReport($exception)) {
            return;
        }

        if (is_callable($reportCallable = [$exception, 'report'])) {
            return $this->container->call($reportCallable);
        }

        try {
            $logger = $this->container->make(LoggerInterface::class);
        } catch (Exception $ex) {
            throw $exception;
        }
        
        $logger->channel('daily')->error(
            $exception->getMessage(),
            array_merge($this->context(), ['exception' => $exception]
        ));
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, \Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {
            return response()->error('要找的东西不见了~', 404);
        }

        if ($exception instanceof ThrottleRequestsException) {
            return response()->error(__('throttle.Too Many Attempts.', ['retry-after' => $exception->getHeaders()['Retry-After']]), 429);
        }

        if ($exception instanceof AccessDeniedHttpException || $exception instanceof AuthorizationException) {
            return response()->error('Access Deny', 403);
        }
        
        return parent::render($request, $exception);
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        if ($e->response) {
            return $e->response;
        }

        return response()->error($e->errors());
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson()
                    ? response()->json(['message' => $exception->getMessage()], 401)
                    : redirect()->guest($exception->redirectTo() ?? '/auth/login');
    }
}
