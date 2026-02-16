<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\Rule;

final class SupplierController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $suppliers = Supplier::query()
            ->when($request->filled('is_active'), fn ($q) => $q->where('is_active', $request->boolean('is_active')))
            ->when($request->string('country_code')->isNotEmpty(), fn ($q) => $q->where('country_code', $request->string('country_code')))
            ->when($request->filled('is_eu_member'), fn ($q) => $q->where('is_eu_member', $request->boolean('is_eu_member')))
            ->paginate($request->integer('per_page', 15));

        return SupplierResource::collection($suppliers);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255', Rule::unique('suppliers', 'code')->where('team_id', $request->user()->team_id)],
            'company_name' => ['required', 'string', 'max:255'],
            'trade_name' => ['nullable', 'string', 'max:255'],
            'headquarters' => ['nullable', 'array'],
            'mailing_address' => ['nullable', 'array'],
            'country_code' => ['nullable', 'string', 'max:2'],
            'is_eu_member' => ['sometimes', 'boolean'],
            'tax_number' => ['nullable', 'string', 'max:255'],
            'eu_tax_number' => ['nullable', 'string', 'max:255'],
            'company_registration_number' => ['nullable', 'string', 'max:255'],
            'bank_account_number' => ['nullable', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'string', 'url', 'max:255'],
            'rating' => ['nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $supplier = Supplier::query()->create($validated);

        return (new SupplierResource($supplier))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Supplier $supplier): SupplierResource
    {
        return new SupplierResource($supplier);
    }

    public function update(Request $request, Supplier $supplier): SupplierResource
    {
        $validated = $request->validate([
            'code' => ['sometimes', 'string', 'max:255', Rule::unique('suppliers', 'code')->ignore($supplier->id)->where('team_id', $request->user()->team_id)],
            'company_name' => ['sometimes', 'string', 'max:255'],
            'trade_name' => ['nullable', 'string', 'max:255'],
            'headquarters' => ['nullable', 'array'],
            'mailing_address' => ['nullable', 'array'],
            'country_code' => ['nullable', 'string', 'max:2'],
            'is_eu_member' => ['sometimes', 'boolean'],
            'tax_number' => ['nullable', 'string', 'max:255'],
            'eu_tax_number' => ['nullable', 'string', 'max:255'],
            'company_registration_number' => ['nullable', 'string', 'max:255'],
            'bank_account_number' => ['nullable', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'string', 'url', 'max:255'],
            'rating' => ['nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $supplier->update($validated);

        return new SupplierResource($supplier);
    }

    public function destroy(Supplier $supplier): JsonResponse
    {
        $supplier->delete();

        return response()->json(null, 204);
    }
}
