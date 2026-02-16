<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\Rule;

final class TeamController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorizeSuperAdmin($request);

        $teams = Team::query()
            ->when($request->filled('is_active'), fn ($q) => $q->where('is_active', $request->boolean('is_active')))
            ->withCount('users')
            ->paginate($request->integer('per_page', 15));

        return TeamResource::collection($teams);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorizeSuperAdmin($request);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:100', 'unique:teams,slug'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $team = Team::query()->create($validated);

        return (new TeamResource($team->loadCount('users')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, Team $team): TeamResource
    {
        $this->authorizeSuperAdmin($request);

        return new TeamResource($team->loadCount('users'));
    }

    public function update(Request $request, Team $team): TeamResource
    {
        $this->authorizeSuperAdmin($request);

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'slug' => ['sometimes', 'string', 'max:100', Rule::unique('teams', 'slug')->ignore($team->id)],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $team->update($validated);

        return new TeamResource($team->loadCount('users'));
    }

    public function destroy(Request $request, Team $team): JsonResponse
    {
        $this->authorizeSuperAdmin($request);

        $team->delete();

        return response()->json(null, 204);
    }

    private function authorizeSuperAdmin(Request $request): void
    {
        if (! $request->user()->is_super_admin) {
            abort(403, 'Only super admins can manage teams.');
        }
    }
}
