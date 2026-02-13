<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ __('Inventory Count Sheet') }} - {{ $inventory->inventory_number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0 0 5px;
        }
        .meta {
            margin-bottom: 20px;
        }
        .meta table {
            width: 100%;
        }
        .meta td {
            padding: 3px 8px;
            vertical-align: top;
        }
        .meta .label {
            font-weight: bold;
            width: 150px;
        }
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.items th,
        table.items td {
            border: 1px solid #999;
            padding: 6px 8px;
            text-align: left;
        }
        table.items th {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }
        table.items td.number {
            text-align: right;
        }
        table.items td.empty {
            background-color: #fafafa;
        }
        .footer {
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .signatures {
            margin-top: 40px;
        }
        .signatures table {
            width: 100%;
        }
        .signatures td {
            width: 33%;
            text-align: center;
            padding-top: 40px;
            border-top: 1px solid #333;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ __('Inventory Count Sheet') }}</h1>
        <p>{{ $inventory->inventory_number }}</p>
    </div>

    <div class="meta">
        <table>
            <tr>
                <td class="label">{{ __('Warehouse') }}:</td>
                <td>{{ $inventory->warehouse?->name ?? '-' }}</td>
                <td class="label">{{ __('Date') }}:</td>
                <td>{{ $inventory->inventory_date?->format('Y-m-d') ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">{{ __('Conducted By') }}:</td>
                <td>{{ $inventory->conductedBy?->first_name ?? '-' }} {{ $inventory->conductedBy?->last_name ?? '' }}</td>
                <td class="label">{{ __('Status') }}:</td>
                <td>{{ $inventory->status?->getLabel() ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">{{ __('Type') }}:</td>
                <td>{{ $inventory->type?->getLabel() ?? '-' }}</td>
                <td class="label">{{ __('Items') }}:</td>
                <td>{{ $inventory->inventoryLines->count() }}</td>
            </tr>
        </table>
    </div>

    <table class="items">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 15%;">{{ __('SKU') }}</th>
                <th style="width: 30%;">{{ __('Product Name') }}</th>
                <th style="width: 15%;">{{ __('System Qty') }}</th>
                <th style="width: 15%;">{{ __('Actual Qty') }}</th>
                <th style="width: 20%;">{{ __('Variance') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($inventory->inventoryLines as $index => $line)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $line->product?->sku ?? '-' }}</td>
                    <td>{{ $line->product?->name ?? '-' }}</td>
                    <td class="number">{{ number_format($line->system_quantity) }}</td>
                    <td class="number empty">&nbsp;</td>
                    <td class="number empty">&nbsp;</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">
                        {{ __('No items in this inventory count.') }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($inventory->notes)
        <div style="margin-top: 15px;">
            <strong>{{ __('Notes') }}:</strong> {{ $inventory->notes }}
        </div>
    @endif

    <div class="signatures">
        <table>
            <tr>
                <td>{{ __('Conducted By') }}</td>
                <td>{{ __('Verified By') }}</td>
                <td>{{ __('Approved By') }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
