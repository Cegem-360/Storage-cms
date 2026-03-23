<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Filament\Auth\Http\Responses\LoginResponse as BaseLoginResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Livewire\Features\SupportRedirects\Redirector;
use Override;

final class LoginResponse extends BaseLoginResponse
{
    #[Override]
    public function toResponse($request): RedirectResponse|Redirector
    {
        $team = Auth::user()->teams->first();

        if (! $team) {
            return redirect()->route('filament.admin.tenant.registration');
        }

        return redirect()->intended(
            route('filament.admin.pages.dashboard', ['tenant' => $team->slug])
        );
    }
}
