<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('auth', [\App\Http\Middleware\EnsureUserProfileIsComplete::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->respond(function (Response $response) {
            if ($response->getStatusCode() === 419) {
                return back()->with([
                    'message' => 'The page expired, please refresh and try again.',
                ]);
            }
            if ($response->getStatusCode() === 500) {
                // Check if an exception exists
                // $errorMessage = $response->exception ? $response->exception->getMessage() . ' in ' . $response->exception->getFile() . ' at Line: ' . $response->exception->getLine() : 'No exception available';

                // // Send email notification to admin
                // // Mail::raw("Server Error: {$exception->getMessage()}\n\nIn File: {$exception->getFile()}:{$exception->getLine()}", function ($message) {
                // //     $message->to('info@fortranhouse.com')->subject('Venus Error Alert');
                // // });
                // Mail::raw("A server error occurred: {$errorMessage}", function ($message) {
                //     $message->to('info@fortranhouse.com')->subject('Venus Error Alert');
                // });
                //if env is production
                if (app()->environment('production')) {
                    // Log the error
                    // \Log::error('Server Error: ' . $response->exception->getMessage(), [
                    //     'file' => $response->exception->getFile(),
                    //     'line' => $response->exception->getLine(),
                    // ]);
                    return redirect()->route('dashboard')->with([
                        'error' => 'Server error. Please contact support.',
                    ]);
                }
            }

            return $response;
        });
    })->create();
