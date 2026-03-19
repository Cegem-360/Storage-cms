<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\Scopes\TeamScope;
use App\Models\Team;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTeam
{
    public static function bootBelongsToTeam(): void
    {
        static::addGlobalScope(new TeamScope());

        static::creating(static function ($model): void {
            if (empty($model->team_id) && app()->bound(Team::CONTAINER_BINDING)) {
                $team = resolve(Team::CONTAINER_BINDING);

                if ($team instanceof Team) {
                    $model->team_id = $team->getKey();
                }
            }
        });
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
