<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\Team;
use App\Models\User;
use Madbox99\UserTeamSync\Events\TeamCreatedFromSync;

final class AssignUserToTenantOnTeamSync
{
    public function handle(TeamCreatedFromSync $event): void
    {
        /** @var Team $team */
        $team = $event->team;

        $userEmail = request()->input('user_email');

        if ($userEmail) {
            User::query()
                ->where('email', $userEmail)
                ->whereNull('tenant_id')
                ->update(['tenant_id' => $team->id]);
        }
    }
}
