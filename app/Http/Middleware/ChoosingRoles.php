<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class ChoosingRoles
{
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);

        $user = $request->user();
        if (!$user) {
            return redirect()->route('login');
        }
        $roles = explode('|', $roles);
        
        $userRole = User::ROLES[$user->role];

        if (!in_array($userRole, $roles)) {
            abort(401);
        }
    }
}
