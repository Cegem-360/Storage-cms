<?php

declare(strict_types=1);

namespace App\Models;

use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;
use Override;

final class User extends Authenticatable implements FilamentUser, HasTenants
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_super_admin',
        'is_active',
        'team_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    public function getTenants(Panel $panel): Collection
    {
        return $this->teams;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->teams()->whereKey($tenant)->exists();
    }

    public function isAdmin(): bool
    {
        return $this->is_super_admin;
    }

    #[Override]
    protected static function booted(): void
    {
        self::addGlobalScope('tenant', static function (Builder $query): void {
            $team = Filament::getTenant()
                ?? (app()->bound(Team::CONTAINER_BINDING) ? resolve(Team::CONTAINER_BINDING) : null);

            if ($team instanceof Team) {
                $query->whereHas(
                    'teams',
                    static fn (Builder $query): Builder => $query->where('teams.id', $team->getKey()),
                );
            }
        });
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_super_admin' => 'boolean',
            'is_active' => 'boolean',
        ];
    }
}
