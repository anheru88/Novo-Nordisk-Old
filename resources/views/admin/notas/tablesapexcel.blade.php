<table id="datatable_full" class="table table-hover table-responsive">
    <thead>
        <tr>
            <th style="background-color: #1E2A6E; color:#ffffff">Código cliente</th>
            <th style="background-color: #1E2A6E; color:#ffffff">Material</th>
            <th style="background-color: #1E2A6E; color:#ffffff">Real QTY</th>
            <th style="background-color: #1E2A6E; color:#ffffff">Valor NC</th>
            <th style="background-color: #1E2A6E; color:#ffffff">NC Individual</th>
            <th style="background-color: #1E2A6E; color:#ffffff">Factura</th>
            <th style="background-color: #1E2A6E; color:#ffffff">Concepto</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($notes as $note )
        @foreach ($note->productsBills as $product)
        @foreach ($product->details as $detail)
        <tr>
            <td>{{ $product->client_sap_code }}</td>
            <td>{{ $detail->prod_sap_code }}</td>
            <td>{{ $detail->real_qty }}</td>
            <td>{{ $detail->nc_value }}</td>
            <td>{{ $detail->nc_individual  }}</td>
            <td>{{ $product->bill }}</td>
            <td>{{ $detail->concept }}</td>
        </tr>
        @endforeach
        @endforeach
        @endforeach
    </tbody>
</table>
