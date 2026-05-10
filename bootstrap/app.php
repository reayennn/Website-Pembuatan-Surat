<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // Redirect tamu ke halaman login yang sesuai
        $middleware->redirectGuestsTo(function (\Illuminate\Http\Request $request) {
            $path = $request->path();

            // Route masyarakat → login masyarakat
            if (str_starts_with($path, 'pengajuan') || str_starts_with($path, 'dashboard')) {
                return route('login.masyarakat');
            }

            // Route admin / kepala-desa / petugas → login petugas
            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Http\Exceptions\PostTooLargeException $e, \Illuminate\Http\Request $request) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ukuran file/data yang Anda unggah terlalu besar dan melebihi batas sistem!');
        });
    })->create();
