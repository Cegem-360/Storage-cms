<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Filament\Auth\Http\Responses\RegistrationResponse as BaseRegistrationResponse;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;
use Override;

final class RegistrationResponse extends BaseRegistrationResponse
{
    #[Override]
    public function toResponse($request): RedirectResponse|Redirector
    {
        return to_route('dashboard');
    }
}
