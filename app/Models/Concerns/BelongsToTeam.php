<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

trait BelongsToTeam
{
    public static function bootBelongsToTeam(): void
    {
        static::addGlobalScope('team', function (Builder $query): void {
            if (! Auth::hasUser()) {
                return;
            }

            /** @var User $user */
            $user = Auth::user();

            if ($user->is_super_admin) {
                return;
            }

            $query->where(
                $query->getModel()->getTable().'.team_id',
                $user->team_id,
            );
        });

        static::creating(function (Model $model): void {
            if (! Auth::hasUser()) {
                return;
            }

            if ($model->team_id !== null) {
                return;
            }

            /** @var User $user */
            $user = Auth::user();
            $model->team_id = $user->team_id;
        });
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
