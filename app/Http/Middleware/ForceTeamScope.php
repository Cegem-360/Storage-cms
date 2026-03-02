<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class ForceTeamScope
{
    public function handle(Request $request, Closure $next): Response
    {
        app()->instance('force_team_scope', true);

        return $next($request);
    }
}
