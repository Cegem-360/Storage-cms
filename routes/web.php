<?php

declare(strict_types=1);

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

Route::get('/', function (): View|Factory {
    return view('home');
})->name('home');

Route::get('/language/{locale}', function (string $locale): RedirectResponse {
    if (! in_array($locale, ['en', 'hu'], true)) {
        abort(400);
    }
    $cookie = cookie('locale', $locale, 60 * 24 * 365);
    $referer = request()->headers->get('referer');
    $redirectUrl = $referer ?: url()->previous();

    return redirect($redirectUrl)->withCookie($cookie);
})->name('language.switch');
