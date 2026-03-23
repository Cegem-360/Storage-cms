<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use App\Models\Team;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

final class TeamThroughScope implements Scope
{
    /** @param Builder<Model> $builder */
    public function apply(Builder $builder, Model $model): void
    {
        $team = app()->bound(Team::CONTAINER_BINDING)
            ? resolve(Team::CONTAINER_BINDING)
            : null;

        if ($team instanceof Team && property_exists($model, 'teamRelationship')) {
            $builder->whereHas($model->teamRelationship);
        }
    }
}
