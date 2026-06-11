<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MakerMiddleware
{
    /**
     * Only allow makers (or admins) through.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || (! $user->isMaker() && ! $user->isAdmin())) {
            return redirect()->route('home')
                ->with('error', 'Only makers can access that page.');
        }

        return $next($request);
    }
}
