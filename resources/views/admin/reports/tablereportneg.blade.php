<table id="datatable_full_neg" data-toggle="table" data-pagination="true" data-search="true" class="table table-striped table-hover">
    <thead>
        <tr>
            <th data-sortable="true"># Negociación</th>
            <th data-sortable="true"># Cotización</th>
            <th data-sortable="true">Cliente</th>
            <th data-sortable="true">Estado de la Negociación</th>
            <th data-sortable="true">Canal</th>
            <th data-sortable="true">CAM</th>
            <th data-sortable="true">Producto</th>
            <th data-sortable="true">Condición financiera</th>
            <th data-sortable="true">Nivel de autorización</th>
            <th data-sortable="true">Vigente desde</th>
            <th data-sortable="true">Vigente hasta</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($results1 as $key=>$result)
        <tr>
            <td><a href="{{ route('negociaciones.show', $result->id_negotiation) }}" target="_blank">
                    {{ $result->negotiation->negotiation_consecutive }}
                </a>
            </td>
            <td><a href="{{ route('cotizaciones.show', $result->id_quotation) }}" target="_blank">
                    {{ $result->quotation->quota_consecutive }}
                </a>
            </td>
            <td>{{ $result->client->client_name }}</td>
            @if (!empty(statushelp($result->quotation->is_authorized, $result->quotation->pre_aproved, $result->quotation->status_id)))
                <td>{!! statusCot($result->quotation->is_authorized, $result->quotation->pre_aproved) !!}</td>
            @else
                <td>N/A</td>
            @endif
            <td>{{ $result->quotation->channel->channel_name }}</td>
            <td>{{ $result->quotation->creator->nickname }}</td>
            <td>{{ $result->product->prod_name }}</td>
            @if (isset($result->negotiation))
                <td>{{ $result->client->payterm->payterm_name }}</td>
            @else
                <td>{{ $result->payterm->payterm_name }}</td>
            @endif
            @if ($result->quotation->id_auth_level > 1)
            <td>{{ $result->quotation->id_auth_level }}</td>
            @else
            <td>No requiere</td>
            @endif
            <td>{{ date('d-m-Y',strtotime($result->quotation->quota_date_ini)) }}</td>
            <td>{{ date('d-m-Y',strtotime($result->quotation->quota_date_end)) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
