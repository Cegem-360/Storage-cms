<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\TeamFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;

final class Team extends Model
{
    /** @use HasFactory<TeamFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'is_active',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function settings(): HasMany
    {
        return $this->hasMany(TeamSetting::class);
    }

    public function aiTokenUsages(): HasMany
    {
        return $this->hasMany(AiTokenUsage::class);
    }

    public function getSetting(string $key, mixed $default = null): mixed
    {
        return $this->settings->firstWhere('key', $key)?->value ?? $default;
    }

    public function setSetting(string $key, mixed $value): void
    {
        $this->settings()->updateOrCreate(
            ['key' => $key],
            ['value' => $value],
        );

        $this->unsetRelation('settings');
    }

    public function recordTokenUsage(int $promptTokens, int $completionTokens): void
    {
        $usage = $this->aiTokenUsages()->firstOrCreate(
            ['month' => now()->format('Y-m')],
            ['prompt_tokens' => 0, 'completion_tokens' => 0, 'total_tokens' => 0],
        );

        $usage->increment('prompt_tokens', $promptTokens);
        $usage->increment('completion_tokens', $completionTokens);
        $usage->increment('total_tokens', $promptTokens + $completionTokens);
    }

    public function hasExceededTokenLimit(): bool
    {
        $limit = (int) $this->getSetting('ai_monthly_token_limit', 0);

        if ($limit === 0) {
            return false;
        }

        $usage = $this->aiTokenUsages()
            ->where('month', now()->format('Y-m'))
            ->first();

        return $usage !== null && $usage->total_tokens >= $limit;
    }

    public function getTokenUsagePercentage(): float
    {
        $limit = (int) $this->getSetting('ai_monthly_token_limit', 0);

        if ($limit === 0) {
            return 0.0;
        }

        $usage = $this->aiTokenUsages()
            ->where('month', now()->format('Y-m'))
            ->first();

        $used = $usage?->total_tokens ?? 0;

        return min(100.0, ($used / $limit) * 100);
    }

    /**
     * @return array<string, string>
     */
    #[Override]
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
