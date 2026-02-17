<?php

declare(strict_types=1);

use App\Models\AiTokenUsage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->team = $this->user->team;
    $this->actingAs($this->user);
});

describe('Team token usage tracking', function (): void {
    it('records token usage for the current month', function (): void {
        $this->team->recordTokenUsage(100, 50);

        $usage = AiTokenUsage::query()
            ->where('team_id', $this->team->id)
            ->where('month', now()->format('Y-m'))
            ->first();

        expect($usage)->not->toBeNull()
            ->and($usage->prompt_tokens)->toBe(100)
            ->and($usage->completion_tokens)->toBe(50)
            ->and($usage->total_tokens)->toBe(150);
    });

    it('accumulates token usage across multiple calls', function (): void {
        $this->team->recordTokenUsage(100, 50);
        $this->team->recordTokenUsage(200, 100);

        $usage = AiTokenUsage::query()
            ->where('team_id', $this->team->id)
            ->where('month', now()->format('Y-m'))
            ->first();

        expect($usage->prompt_tokens)->toBe(300)
            ->and($usage->completion_tokens)->toBe(150)
            ->and($usage->total_tokens)->toBe(450);
    });

    it('returns false for hasExceededTokenLimit when no limit is set', function (): void {
        $this->team->recordTokenUsage(999999, 999999);

        expect($this->team->hasExceededTokenLimit())->toBeFalse();
    });

    it('returns false for hasExceededTokenLimit when limit is zero', function (): void {
        $this->team->setSetting('ai_monthly_token_limit', 0);
        $this->team->recordTokenUsage(999999, 999999);

        expect($this->team->hasExceededTokenLimit())->toBeFalse();
    });

    it('returns false for hasExceededTokenLimit when under limit', function (): void {
        $this->team->setSetting('ai_monthly_token_limit', 10000);
        $this->team->recordTokenUsage(100, 50);

        expect($this->team->hasExceededTokenLimit())->toBeFalse();
    });

    it('returns true for hasExceededTokenLimit when at limit', function (): void {
        $this->team->setSetting('ai_monthly_token_limit', 150);
        $this->team->recordTokenUsage(100, 50);

        expect($this->team->hasExceededTokenLimit())->toBeTrue();
    });

    it('returns true for hasExceededTokenLimit when above limit', function (): void {
        $this->team->setSetting('ai_monthly_token_limit', 100);
        $this->team->recordTokenUsage(100, 50);

        expect($this->team->hasExceededTokenLimit())->toBeTrue();
    });

    it('calculates token usage percentage correctly', function (): void {
        $this->team->setSetting('ai_monthly_token_limit', 1000);
        $this->team->recordTokenUsage(250, 250);

        expect($this->team->getTokenUsagePercentage())->toBe(50.0);
    });

    it('returns zero percentage when no limit is set', function (): void {
        $this->team->recordTokenUsage(100, 50);

        expect($this->team->getTokenUsagePercentage())->toBe(0.0);
    });

    it('caps percentage at 100', function (): void {
        $this->team->setSetting('ai_monthly_token_limit', 100);
        $this->team->recordTokenUsage(200, 100);

        expect($this->team->getTokenUsagePercentage())->toBe(100.0);
    });

    it('does not count previous month usage', function (): void {
        AiTokenUsage::factory()->create([
            'team_id' => $this->team->id,
            'month' => now()->subMonth()->format('Y-m'),
            'prompt_tokens' => 9999,
            'completion_tokens' => 9999,
            'total_tokens' => 19998,
        ]);

        $this->team->setSetting('ai_monthly_token_limit', 1000);

        expect($this->team->hasExceededTokenLimit())->toBeFalse();
    });
});
