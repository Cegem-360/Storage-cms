<?php

declare(strict_types=1);

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

describe('GET /api/v1/orders', function (): void {
    it('lists orders', function (): void {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        Order::factory()->salesOrder()->count(3)->recycle($user->team)->create();

        $response = $this->getJson('/api/v1/orders');

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'order_number', 'type', 'status', 'total_amount'],
                ],
                'links',
                'meta',
            ]);
    });

    it('filters orders by status', function (): void {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        Order::factory()->salesOrder()->draft()->count(2)->recycle($user->team)->create();
        Order::factory()->salesOrder()->confirmed()->recycle($user->team)->create();

        $response = $this->getJson('/api/v1/orders?status=draft');

        $response->assertOk()
            ->assertJsonCount(2, 'data');

        expect(collect($response->json('data'))->pluck('status'))->each(
            fn ($status) => $status->toBe(OrderStatus::DRAFT->value)
        );
    });

    it('requires authentication', function (): void {
        $response = $this->getJson('/api/v1/orders');

        $response->assertUnauthorized();
    });
});

describe('GET /api/v1/orders/{id}', function (): void {
    it('shows a single order with lines', function (): void {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $order = Order::factory()->salesOrder()->recycle($user->team)->create();
        $product = Product::factory()->recycle($user->team)->create();
        OrderLine::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
        ]);

        $response = $this->getJson("/api/v1/orders/{$order->id}");

        $response->assertOk()
            ->assertJsonPath('data.id', $order->id)
            ->assertJsonPath('data.order_number', $order->order_number)
            ->assertJsonStructure([
                'data' => [
                    'id', 'order_number', 'type', 'status', 'total_amount',
                    'customer', 'supplier',
                    'orderLines' => [
                        '*' => ['id', 'quantity', 'unit_price', 'product'],
                    ],
                ],
            ]);
    });
});

describe('GET /api/v1/orders/{order}/lines', function (): void {
    it('lists order lines', function (): void {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $order = Order::factory()->salesOrder()->recycle($user->team)->create();
        OrderLine::factory()->count(3)->create(['order_id' => $order->id]);

        $response = $this->getJson("/api/v1/orders/{$order->id}/lines");

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'quantity', 'unit_price', 'product'],
                ],
            ]);
    });

    it('shows a single order line', function (): void {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $order = Order::factory()->salesOrder()->recycle($user->team)->create();
        $line = OrderLine::factory()->create(['order_id' => $order->id]);

        $response = $this->getJson("/api/v1/orders/{$order->id}/lines/{$line->id}");

        $response->assertOk()
            ->assertJsonPath('data.id', $line->id)
            ->assertJsonPath('data.quantity', $line->quantity)
            ->assertJsonStructure([
                'data' => ['id', 'quantity', 'unit_price', 'product'],
            ]);
    });
});

describe('POST /api/v1/orders', function (): void {
    it('cannot create orders via API', function (): void {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/v1/orders', [
            'order_number' => 'ORD-99999',
            'type' => 'sales',
            'status' => 'draft',
        ]);

        $response->assertMethodNotAllowed();
    });
});
