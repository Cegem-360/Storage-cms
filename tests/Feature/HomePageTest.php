<?php

declare(strict_types=1);

use App\Models\User;

it('loads the home page for guests', function (): void {
    $this->get('/')->assertOk();
});

it('loads the home page for authenticated users', function (): void {
    $this->actingAs(User::factory()->create());

    $this->get('/')->assertOk();
});

it('loads the language switch route', function (): void {
    $this->get('/language/hu')->assertRedirect();
    $this->get('/language/en')->assertRedirect();
});

it('rejects invalid locale', function (): void {
    $this->get('/language/fr')->assertStatus(400);
});
