<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ __('Product Label') }} - {{ $product->sku }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 10px;
        }
        .label {
            width: 280px;
            border: 1px solid #333;
            padding: 10px;
            text-align: center;
        }
        .label h2 {
            font-size: 14px;
            margin: 0 0 5px;
        }
        .label .sku {
            font-size: 11px;
            color: #666;
            margin-bottom: 8px;
        }
        .label .barcode-image {
            margin: 8px 0;
        }
        .label .barcode-image img {
            max-width: 250px;
            height: 50px;
        }
        .label .barcode-text {
            font-size: 12px;
            font-family: monospace;
            letter-spacing: 2px;
            margin-top: 4px;
        }
        .label .price {
            font-size: 16px;
            font-weight: bold;
            margin-top: 8px;
        }
    </style>
</head>
<body>
    <div class="label">
        <h2>{{ $product->name }}</h2>
        <div class="sku">{{ $product->sku }}</div>

        @if($product->barcode && $barcodeImage)
            <div class="barcode-image">
                <img src="data:image/png;base64,{{ $barcodeImage }}" alt="{{ $product->barcode }}">
            </div>
            <div class="barcode-text">{{ $product->barcode }}</div>
        @endif

        @if($product->price)
            <div class="price">{{ number_format((float) $product->price, 0, '.', ' ') }} Ft</div>
        @endif
    </div>
</body>
</html>
