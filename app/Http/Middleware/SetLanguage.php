<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $locale = setting('language');

            if (in_array($locale, array_keys(config('libredesk.languages')))) {
                App::setLocale($locale);
            }

        } catch (\Exception $exception) {

        }

        return $next($request);
    }
}
