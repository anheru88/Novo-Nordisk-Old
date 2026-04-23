<table id="datatable_full" class="table table-hover table-responsive">
    <thead>
        <tr>
            <th># Negociación</th>
            <th># Cotización</th>
            <th>Escala de Cliente</th>
            <th>Concepto</th>
            <th>Aclaración</th>
            <th>Código SAP cliente</th>
            <th>Cliente</th>
            <th>Ciudad de Cliente</th>
            <th>Estado</th>
            <th>Canal</th>
            <th>CAM</th>
            <th>Código SAP producto</th>
            <th>Producto</th>
            <th>Marca</th>
            <th>Precio cotización</th>
            <th>Condición financiera</th>
            <th>Porcentaje de descuento</th>
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
        @endphp
            <tr>
                <td><a href="{{ route('negociaciones.show', $result->id_negotiation) }}">
                        {{ $result->negotiation->negotiation_consecutive }}
                    </a>
                </td>
                <td><a href="{{ route('cotizaciones.show', $result->id_quotation) }}">
                        {{ $result->quotation->quota_consecutive }}
                    </a>
                </td>
                @if (empty(scalename($result->product->id_product)))
                <td>Escala</td>
                @else
                <td>{{ scalename($result->product->id_product) }}</td>
                @endif
                @if (empty(conceptname($result->id_concept)))
                    <td>{{ conceptname($result->id_concept) }}</td>
                @else
                    <td>Escala</td>
                @endif
                <td>{{ $result->aclaracion }}</td>
                <td>{{ $result->client->client_sap_code }}</td>
                <td>{{ $result->client->client_name }}</td>
                <td>{{ $result->client->city->loc_name }}</td>
                @if ($result->quotation->status_id > 0)
                <td>{{ $result->quotation->status->status_name }}</td>
                @else
                <td>{{ statushelp($result->quotation->is_authorized, $result->quotation->pre_aproved, $result->quotation->status_id) }} {{ $result->quotation->status_id }}</td>
                @endif
                <td>{{ $result->quotation->channel->channel_name }}</td>
                <td>{{ $result->quotation->creator->nickname }}</td>
                <td>{{ $result->product->prod_sap_code }}</td>
                <td>{{ $result->product->prod_name }}</td>
                @if (isset($result->product->brand->brand_name))
                    <td>{{ $result->product->brand->brand_name }}</td>
                @else
                    <td>Sin Marca</td>
                @endif
                <td>{{ $result->quotation->quota_value }}</td>
                <td>{{ $result->client->payterm->payterm_name }}</td>
                <td>{{ $result->discount }}</td>
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
            {{-- @endforeach --}}
        {{-- @endfor --}}
    </tbody>
</table>
