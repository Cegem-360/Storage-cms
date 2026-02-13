<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\IntrastatLineResource;
use App\Models\IntrastatDeclaration;
use App\Models\IntrastatLine;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class IntrastatLineController extends Controller
{
    public function index(Request $request, IntrastatDeclaration $intrastatDeclaration): AnonymousResourceCollection
    {
        $lines = $intrastatDeclaration->intrastatLines()
            ->with(['product', 'supplier'])
            ->paginate($request->integer('per_page', 15));

        return IntrastatLineResource::collection($lines);
    }

    public function show(IntrastatDeclaration $intrastatDeclaration, IntrastatLine $line): IntrastatLineResource
    {
        $line->load(['product', 'supplier']);

        return new IntrastatLineResource($line);
    }
}
