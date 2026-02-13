<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\IntrastatDeclarationResource;
use App\Models\IntrastatDeclaration;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class IntrastatDeclarationController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $declarations = IntrastatDeclaration::query()
            ->when($request->string('direction')->isNotEmpty(), fn ($q) => $q->where('direction', $request->string('direction')))
            ->when($request->string('status')->isNotEmpty(), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('reference_year'), fn ($q) => $q->where('reference_year', $request->integer('reference_year')))
            ->when($request->filled('reference_month'), fn ($q) => $q->where('reference_month', $request->integer('reference_month')))
            ->paginate($request->integer('per_page', 15));

        return IntrastatDeclarationResource::collection($declarations);
    }

    public function show(IntrastatDeclaration $intrastatDeclaration): IntrastatDeclarationResource
    {
        $intrastatDeclaration->load(['intrastatLines']);

        return new IntrastatDeclarationResource($intrastatDeclaration);
    }
}
