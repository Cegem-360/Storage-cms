<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\AiTokenUsageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

final class AiTokenUsage extends Model
{
    /** @use HasFactory<AiTokenUsageFactory> */
    use HasFactory;

    protected $fillable = [
        'team_id',
        'month',
        'prompt_tokens',
        'completion_tokens',
        'total_tokens',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * @return array<string, string>
     */
    #[Override]
    protected function casts(): array
    {
        return [
            'prompt_tokens' => 'integer',
            'completion_tokens' => 'integer',
            'total_tokens' => 'integer',
        ];
    }
}
