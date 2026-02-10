<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleFromHeader
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $acceptLanguage = $request->header('Accept-Language', 'en');

        $lang = strtolower(explode(',', $acceptLanguage)[0]);
        $lang = explode('-', $lang)[0];

        if (!in_array($lang, ['en', 'pl', 'de'])) {
            $lang = 'en';
        }

        app()->setLocale($lang);

        return $next($request);
    }
}
