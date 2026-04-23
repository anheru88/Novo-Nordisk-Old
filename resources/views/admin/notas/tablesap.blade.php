@php
$counter = 0;
$totalproducts = 0;
$totaldetails = 0;
@endphp
@foreach ($notes as $note )
@foreach ($note->products as $product)
@php $counter += 1 @endphp
10;5341;02;s{{ $product->bill }};{{ $product->client_sap_code }};{{ $product->client_sap_code }};{{ $product->concept }}_{{ $product->client_sap_code }};;;;;ZCR2;200;{{ \Carbon\Carbon::now()->format('Ymd') }}
@foreach ($product->details as $detail)
@php $counter += 1 @endphp
20;{{ $detail->prod_sap_code }};{{ $detail->real_qty }};;{{ $detail->nc_individual }}
@php $totaldetails += 1 @endphp
@endforeach
@php $totalproducts += 1 @endphp
@endforeach
@endforeach
30;{{ $counter }}