<?php

declare(strict_types=1);

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Route;

Route::get('/', fn (): View|Factory => view('home'))->name('home');

Route::get('/language/{locale}', function (string $locale): RedirectResponse {
    if (! in_array($locale, ['en', 'hu'], true)) {
        abort(400);
    }
    $cookie = cookie('locale', $locale, 60 * 24 * 365);
    $referer = request()->headers->get('referer');
    $redirectUrl = $referer ?: url()->previous();

    return redirect($redirectUrl)->withCookie($cookie);
})->name('language.switch');

// Guest routes - redirect to Filament auth pages
Route::middleware(['guest'])->group(function (): void {
    Route::get('/login', fn (): Redirector|RedirectResponse => to_route('filament.admin.auth.login'))->name('login');
    Route::get('/register', fn (): Redirector|RedirectResponse => to_route('filament.admin.auth.login'))->name('register');
});
