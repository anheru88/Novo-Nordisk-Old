<table id="datatable_full_cot" data-toggle="table" data-pagination="true" data-search="true" class="table table-striped table-hover">
    <thead>
        <tr>
            <th data-sortable="true"># Cotización</th>
            {{-- <th data-sortable="true">Código SAP cliente</th> --}}
            <th data-sortable="true">Cliente</th>
            {{-- <th data-sortable="true">Ciudad de Cliente</th> --}}
            <th data-sortable="true">Estado</th>
            <th data-sortable="true">Canal</th>
            <th data-sortable="true">CAM</th>
            {{-- <th data-sortable="true">Código SAP producto</th> --}}
            <th data-sortable="true">Producto</th>
            {{-- <th data-sortable="true">Marca</th> --}}
            <th data-sortable="true">Precio cotización</th>
            <th data-sortable="true">Condición financiera</th>
            {{-- <th data-sortable="true">Aprobada por</th> --}}
            <th data-sortable="true">Nivel de autorización</th>
            {{-- <th data-sortable="true">Fecha de creación</th> --}}
            <th data-sortable="true">Vigente desde</th>
            <th >Vigente hasta</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($results as $key=>$result)
        {{-- @php
            dd( $type );
        @endphp --}}
        <tr>
            <td><a href="{{ route('cotizaciones.show', $result->id_quotation) }}">
                    {{ $result->quotation->quota_consecutive }}
                </a>
            </td>
            {{-- <td>{{ $result->client->client_sap_code }}</td> --}}
            <td>{{ $result->client->client_name }}</td>
            {{-- <td>{{ $result->client->city->loc_name }}</td> --}}
            <td>{!! statusCot($result->quotation->is_authorized, $result->quotation->pre_aproved) !!}</td>
            <td>{{ $result->quotation->channel->channel_name }}</td>
            <td>{{ $result->quotation->creator->nickname }}</td>
            {{-- <td>{{ $result->product->prod_sap_code }}</td> --}}
            <td>{{ $result->product->prod_name }}</td>
            {{-- <td>{{ $result->product->brand->brand_name }}</td> --}}
            <td>${{ number_format( $result->totalValue,0, ",", ".") }}</td>
            @if (isset($result->negotiation))
                <td>{{ $result->client->payterm->payterm_name }}</td>
            @else
                <td>{{ $result->payterm->payterm_name }}</td>      
            @endif
            {{-- @if ($result->quotation->id_authorizer_user != "")
            <td>{{ $result->quotation->users->nickname }}</td>
            @else
            <td>No requiere</td>
            @endif --}}
            @if ($result->quotation->id_auth_level > 1)
            <td>{{ $result->quotation->id_auth_level }}</td>
            @else
            <td>No requiere</td>
            @endif
            {{-- <td>{{ $result->created_at }}</td> --}}
            <td>{{ date('d-m-Y',strtotime($result->quotation->quota_date_ini)) }}</td>
            <td>{{ date('d-m-Y',strtotime($result->quotation->quota_date_end)) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>