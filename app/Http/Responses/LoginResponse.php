<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Filament\Auth\Http\Responses\LoginResponse as BaseLoginResponse;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;
use Override;

final class LoginResponse extends BaseLoginResponse
{
    #[Override]
    public function toResponse($request): RedirectResponse|Redirector
    {
        return redirect()->intended(route('dashboard'));
    }
}
