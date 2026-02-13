<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

describe('POST /api/tokens/create', function (): void {
    it('creates a token with valid credentials', function (): void {
        $user = User::factory()->create();

        $response = $this->postJson('/api/tokens/create', [
            'email' => $user->email,
            'password' => 'password',
            'device_name' => 'test-device',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['token']);

        expect($response->json('token'))->toBeString()->not->toBeEmpty();
    });

    it('fails with invalid credentials', function (): void {
        $user = User::factory()->create();

        $response = $this->postJson('/api/tokens/create', [
            'email' => $user->email,
            'password' => 'wrong-password',
            'device_name' => 'test-device',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    });

    it('validates required fields', function (): void {
        $response = $this->postJson('/api/tokens/create', []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email', 'password', 'device_name']);
    });
});

describe('DELETE /api/tokens/revoke', function (): void {
    it('revokes the current token', function (): void {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->deleteJson('/api/tokens/revoke');

        $response->assertNoContent();
    });
});

describe('GET /api/user', function (): void {
    it('returns authenticated user', function (): void {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/user');

        $response->assertOk()
            ->assertJsonFragment([
                'id' => $user->id,
                'email' => $user->email,
            ]);
    });

    it('returns 401 when unauthenticated', function (): void {
        $response = $this->getJson('/api/user');

        $response->assertUnauthorized();
    });
});
