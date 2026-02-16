<?php

declare(strict_types=1);

use App\Filament\Pages\Auth\Login;
use App\Models\User;
use Livewire\Livewire;

it('renders the login page for guests', function (): void {
    $this->get('/admin/login')->assertSuccessful();
});

it('renders the login page as a livewire component', function (): void {
    Livewire::test(Login::class)->assertOk();
});

it('redirects authenticated users away from login', function (): void {
    $this->actingAs(User::factory()->create());

    $this->get('/admin/login')->assertRedirect();
});

it('redirects guests from dashboard to login', function (): void {
    $this->get('/admin')->assertRedirect('/admin/login');
});
