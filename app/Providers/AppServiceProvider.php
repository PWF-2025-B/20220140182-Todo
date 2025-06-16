<?php

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();
        
        Gate::define(ability: 'admin', callback: function ($user): bool {
            return $user->is_admin === true;
        });

        Scramble::configure()
            ->routes(routeResolver: function (Route $route): bool {
                return Str::startsWith(haystack: $route->uri, needles: 'api/');
            })
            ->withDocumentTransformers(cb: function (OpenApi $openApi): void {
                $openApi->secure(
                    securityScheme: SecurityScheme::http(scheme: 'bearer')
                );
            });

    }
}