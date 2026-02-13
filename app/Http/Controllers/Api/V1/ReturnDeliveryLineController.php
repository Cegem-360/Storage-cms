<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReturnDeliveryLineResource;
use App\Models\ReturnDelivery;
use App\Models\ReturnDeliveryLine;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class ReturnDeliveryLineController extends Controller
{
    public function index(Request $request, ReturnDelivery $returnDelivery): AnonymousResourceCollection
    {
        $lines = $returnDelivery->returnDeliveryLines()
            ->with(['product'])
            ->paginate($request->integer('per_page', 15));

        return ReturnDeliveryLineResource::collection($lines);
    }

    public function show(ReturnDelivery $returnDelivery, ReturnDeliveryLine $line): ReturnDeliveryLineResource
    {
        $line->load(['product']);

        return new ReturnDeliveryLineResource($line);
    }
}
