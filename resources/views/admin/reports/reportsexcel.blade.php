<table id="datatable_full" class="table table-hover table-responsive">
    <thead>
        <tr>
            <th># Cotización</th>
            <th>Código SAP cliente</th>
            <th>Cliente</th>
            <th>Escala del Cliente</th>
            <th>Ciudad de Cliente</th>
            <th>Estado</th>
            <th>Canal</th>
            <th>CAM</th>
            <th>Código SAP producto</th>
            <th>Producto</th>
            <th>Marca</th>
            <th>Precio cotización</th>
            <th>Descuento</th>
            <th>Condición financiera</th>
            <th>Aprobada por</th>
            <th>Nivel de autorización</th>
            <th>Fecha de creación</th>
            <th>Vigente desde</th>
            <th>Vigente hasta</th>
        </tr>
    </thead>
    <tbody>
        @php
            $size = sizeof($results);
            // dd($size);
        @endphp
        @for ($i = 0; $i < $size; $i++)
            @foreach ($results[$i] as $key=>$result)
            <tr>
                <td><a href="{{ route('cotizaciones.show', $result->id_quotation) }}">
                        {{ $result->quotation->quota_consecutive }}
                    </a>
                </td>
                <td>{{ $result->client->client_sap_code }}</td>
                <td>{{ $result->client->client_name }}</td>
                @if (empty(scalename($result->product->id_product)))
                <td>Escala</td>
                @else
                <td>{{ scalename($result->product->id_product) }}</td>
                @endif
                <td>{{ $result->client->city->loc_name }}</td>
                <td>{{ statushelp($result->quotation->is_authorized, $result->quotation->pre_aproved) }}</td>
                <td>{{ $result->quotation->channel->channel_name }}</td>
                <td>{{ $result->quotation->creator->nickname }}</td>
                <td>{{ $result->product->prod_sap_code }}</td>
                <td>{{ $result->product->prod_name }}</td>
                <td>{{ $result->product->brand->brand_name }}</td>
                <td>{{ $result->totalValue }}</td>
                <td>{{ $result->pay_discount }}</td>
                <td>{{ $result->payterm->payterm_name }}</td>
                @if ($result->quotation->id_authorizer_user != "")
                <td>{{ $result->quotation->users->nickname }}</td>
                @else
                <td>No requiere</td>
                @endif
                @if ($result->quotation->id_auth_level > 1)
                <td>{{ $result->quotation->id_auth_level }}</td>
                @else
                <td>No requiere</td>
                @endif
                <td>{{ $result->created_at }}</td>
                <td>{{ date('d-m-Y',strtotime($result->quotation->quota_date_ini)) }}</td>
                <td>{{ date('d-m-Y',strtotime($result->quotation->quota_date_end)) }}</td>
            </tr>
            @endforeach
        @endfor
    </tbody>
</table>
