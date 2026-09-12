<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
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

    public function render($request, Throwable $e)
    {
        if ($request->is('api/*') || $request->expectsJson()) {
            // Biarkan framework menangani error validasi dengan format standarnya
            if ($e instanceof ValidationException) {
                return parent::render($request, $e);
            }

            $status = 500;
            $message = 'Terjadi kesalahan pada server';

            if ($e instanceof HttpExceptionInterface) {
                $status = $e->getStatusCode();
                $message = $e->getMessage()
                    ?: (Response::$statusTexts[$status] ?? 'Terjadi kesalahan');
            } elseif ($e instanceof AuthenticationException) {
                $status = 401;
                $message = 'Unauthenticated';
            }

            return response()->json([
                'success' => false,
                'message' => $message,
            ], $status);
        }

        return parent::render($request, $e);
    }
}
