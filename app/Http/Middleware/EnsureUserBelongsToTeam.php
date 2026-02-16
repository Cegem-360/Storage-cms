<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureUserBelongsToTeam
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->is_super_admin) {
            return $next($request);
        }

        if (! $user->team_id) {
            abort(403, 'User is not assigned to a team.');
        }

        if (! $user->team?->is_active) {
            abort(403, 'Team is inactive.');
        }

        return $next($request);
    }
}
