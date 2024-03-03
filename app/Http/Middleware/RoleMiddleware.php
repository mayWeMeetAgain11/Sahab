<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
// use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();
        
        if ($user && in_array($user->role, $roles))
        {
            if (in_array($user->role, $roles))
            {
                return $next($request);
            }
            else
            {
                return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
            }
        }
        return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);

    }

    // public function handle(Request $request, Closure $next, ...$roles)
    // {
    //     $user = $request->user();

    //     if ($user) {
    //         if ($user->role === 'user') {
    //             return $next($request);
    //         } elseif ($user->role === 'manager' && in_array('manager', $roles)) {
    //             $manager = Manager::where('user_id', $user->id)->first();
    //             if ($manager && in_array($manager->role, $roles)) {
    //                 return $next($request);
    //             }
    //         }
    //     }

    //     return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
    // }

}
