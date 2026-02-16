<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\Rule;

final class CustomerController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $customers = Customer::query()
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->string('type')))
            ->when($request->string('search')->isNotEmpty(), fn ($q) => $q->where(function ($q) use ($request): void {
                $q->where('name', 'like', '%'.$request->string('search').'%')
                    ->orWhere('email', 'like', '%'.$request->string('search').'%')
                    ->orWhere('customer_code', 'like', '%'.$request->string('search').'%');
            }))
            ->paginate($request->integer('per_page', 15));

        return CustomerResource::collection($customers);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_code' => ['required', 'string', 'max:255', Rule::unique('customers', 'customer_code')->where('team_id', $request->user()->team_id)],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('customers', 'email')->where('team_id', $request->user()->team_id)],
            'phone' => ['nullable', 'string', 'max:255'],
            'billing_address' => ['nullable', 'array'],
            'shipping_address' => ['nullable', 'array'],
            'credit_limit' => ['sometimes', 'numeric', 'min:0'],
            'balance' => ['sometimes', 'numeric'],
            'type' => ['nullable', 'string', 'max:255'],
        ]);

        $customer = Customer::query()->create($validated);

        return (new CustomerResource($customer))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Customer $customer): CustomerResource
    {
        return new CustomerResource($customer);
    }

    public function update(Request $request, Customer $customer): CustomerResource
    {
        $validated = $request->validate([
            'customer_code' => ['sometimes', 'string', 'max:255', Rule::unique('customers', 'customer_code')->ignore($customer->id)->where('team_id', $request->user()->team_id)],
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'email', 'max:255', Rule::unique('customers', 'email')->ignore($customer->id)->where('team_id', $request->user()->team_id)],
            'phone' => ['nullable', 'string', 'max:255'],
            'billing_address' => ['nullable', 'array'],
            'shipping_address' => ['nullable', 'array'],
            'credit_limit' => ['sometimes', 'numeric', 'min:0'],
            'balance' => ['sometimes', 'numeric'],
            'type' => ['nullable', 'string', 'max:255'],
        ]);

        $customer->update($validated);

        return new CustomerResource($customer);
    }

    public function destroy(Customer $customer): JsonResponse
    {
        $customer->delete();

        return response()->json(null, 204);
    }
}
