<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
        $this->renderable(function (Exception $exception) {

            // UNAUTHORIZED
            if ($exception instanceof UnauthorizedException) {
                return response()->json([
                    'success' => false,
                    'message' => "Votre compte a été déconnecté(e).",
                    'status' => Response::HTTP_UNAUTHORIZED,
                    'errors' => [
                        'error_message' => $exception->getMessage(),
                        'error_file' => $exception->getFile(),
                        'error_line' => $exception->getLine(),
                    ],
                ], Response::HTTP_UNAUTHORIZED);
            }

            // FORBIDDEN
            if ($exception instanceof ForbiddenException) {
                return response()->json([
                    'success' => false,
                    'message' => "Vous n'avez pas le rôle ou l'autorisation appropriée.",
                    'status' => Response::HTTP_FORBIDDEN,
                    'errors' => [
                        'error_message' => $exception->getMessage(),
                        'error_file' => $exception->getFile(),
                        'error_line' => $exception->getLine(),
                    ],
                ], Response::HTTP_FORBIDDEN);
            }

            // MethodNotAllowedHttpException
            if ($exception instanceof MethodNotAllowedHttpException) {
                return response()->json([
                    'success' => false,
                    'message' => "La méthode de la route demandé n'est pas prise en charge.",
                    'status' => Response::HTTP_METHOD_NOT_ALLOWED,
                    'errors' => [
                        'error_message' => $exception->getMessage(),
                        'error_file' => $exception->getFile(),
                        'error_line' => $exception->getLine(),
                    ],
                ], Response::HTTP_METHOD_NOT_ALLOWED);
            }

            // return response()->json(['message' => "Oups !!!!"]);
        });
    }
}
