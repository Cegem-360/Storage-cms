<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderLineResource;
use App\Models\Order;
use App\Models\OrderLine;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class OrderLineController extends Controller
{
    public function index(Request $request, Order $order): AnonymousResourceCollection
    {
        $lines = $order->orderLines()
            ->with(['product'])
            ->paginate($request->integer('per_page', 15));

        return OrderLineResource::collection($lines);
    }

    public function show(Order $order, OrderLine $line): OrderLineResource
    {
        $line->load(['product']);

        return new OrderLineResource($line);
    }
}
