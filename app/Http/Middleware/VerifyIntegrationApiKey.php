<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Team;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

final class VerifyIntegrationApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $provided = (string) $request->header('X-Api-Key');
        $expected = (string) config('services.integration_key');

        abort_if(
            $expected === '' || $provided === '' || ! hash_equals($expected, $provided),
            401,
            'Invalid or missing X-Api-Key header.',
        );

        $user = User::query()
            ->when(config('services.integration_user_email'), fn ($q, $email) => $q->where('email', $email))
            ->orderBy('id')
            ->first();

        abort_unless($user, 503, 'No user available to serve the integration request.');

        Auth::login($user);

        $team = $user->teams->first();

        if ($team instanceof Team) {
            app()->instance(Team::CONTAINER_BINDING, $team);
        }

        return $next($request);
    }
}
