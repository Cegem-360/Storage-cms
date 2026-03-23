<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\User;
use Madbox99\UserTeamSync\Events\UserCreatedFromSync;

final class AssignTenantOnUserSync
{
    public function handle(UserCreatedFromSync $event): void
    {
        /** @var User $user */
        $user = $event->user;

        $teamIds = request()->input('team_ids', []);

        if ($teamIds !== [] && $user->tenant_id === null) {
            $user->updateQuietly(['tenant_id' => $teamIds[0]]);
        }
    }
}
