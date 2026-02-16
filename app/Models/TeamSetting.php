<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class TeamSetting extends Model
{
    /** @use HasFactory<\Database\Factories\TeamSettingFactory> */
    use HasFactory;

    protected $fillable = [
        'team_id',
        'key',
        'value',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
