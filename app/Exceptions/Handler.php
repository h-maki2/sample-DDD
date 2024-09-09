<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use organizationManagement\domain\model\common\exception\IllegalStateException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

     /**
     * Report or log an exception.
     *
     * @param \Throwable $exception
     * @return void
     */
    public function report(Throwable $exception): void
    {
        if ($exception instanceof IllegalStateException) {
            Log::error($exception->getMessage());
        }

        if ($exception instanceof QueryException) {
            Log::error($exception->getMessage());
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception): JsonResponse
    {
        if ($exception instanceof InvalidArgumentException) {
            return response()->json(['message' => '404 Not Found'], 404);
        }

        if ($exception instanceof IllegalStateException) {
            return response()->json(['message' => '400 Bad Request'], 400);
        }

        if ($exception instanceof QueryException) {
            return response()->json(['message' => 'Service Unavailable'], 503);
        }

        return parent::render($request, $exception);
    }
}
