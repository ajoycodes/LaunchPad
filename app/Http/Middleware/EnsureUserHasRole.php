<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Single parameterized gate for role-restricted routes, used as
 * `role:maker` or `role:admin`. Admins always pass, since they can
 * manage everything a maker can.
 */
class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        $allowed = $user && ($user->isAdmin() || $user->role === $role);

        if (! $allowed) {
            $message = $role === 'admin'
                ? 'You are not authorized to access that page.'
                : "Only {$role}s can access that page.";

            return redirect()->route('home')->with('error', $message);
        }

        return $next($request);
    }
}
