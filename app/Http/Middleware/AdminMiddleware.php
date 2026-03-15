<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
 public function handle(Request $request, Closure $next): Response
{
    // Проверавамо: да ли је корисник улогован И да ли му је улога 'admin'
    if (Auth::check() && Auth::user()->role === 'admin') {
        return $next($request); // Све је у реду, пусти га даље
    }

    // Ако није админ, избаци грешку 403 (Забрањен приступ)
    abort(403, 'Ова страница је резервисана за администраторе.');
}
}
