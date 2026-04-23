<table id="datatable_full_cot" data-toggle="table" data-pagination="true" data-search="true"
    class="table table-striped table-hover">
    <thead id="app">
        <tr>
            <th data-sortable="true"># cliente</th>
            <th data-sortable="true">Nombre cliente</th>
            <th data-sortable="true">Client NIT</th>
            <th data-sortable="true">Canal</th>
            <th data-sortable="true">Tipo de cliente</th>
            <th data-sortable="true">Departamento</th>
            <th data-sortable="true">Ciudad</th>
            <th data-sortable="true">Código SAP</th>
            <th data-sortable="true">Cupo</th>
            <th data-sortable="true">Forma de pago</th>
            <th data-sortable="true">Estado del cliente</th>
            {{-- contacto --}}
            <th data-sortable="true">Nombre de contacto</th>
            <th data-sortable="true">Cargo de contacto</th>
            <th data-sortable="true">Dirección</th>
            <th data-sortable="true">Teléfono</th>
            <th data-sortable="true">Email de contacto principal</th>
            <th data-sortable="true">Otros emails de contacto</th>
            <th data-sortable="true">CAM</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($results2 as $key => $result)
            <tr>
                <td><a href="#">
                        {{ $result->id_client }}
                    </a>
                </td>
                <td>{{ $result->client_name }}</td>
                <td>{{ $result->client_nit }}</td>
                <td>{{ $result->channel->channel_name }}</td>
                <td>{{ $result->clientType->type_name }}</td>
                <td>{{ $result->department->loc_name }}</td>
                <td>{{ $result->city->loc_name }}</td>
                <td>{{ $result->client_sap_code }}</td>
                <td>{{ $result->client_credit }}</td>
                @if (isset($result->payterm))
                    <td>{{ $result->payterm->payterm_name }}</td>
                @else
                    <td></td>
                @endif
                <td>{!! statusCli($result->active) !!}</td>
                {{-- contacto --}}
                <td>{{ $result->client_contact }}</td>
                <td>{{ $result->client_position }}</td>
                <td>{{ $result->client_address }}</td>
                <td>{{ $result->client_phone }}</td>
                <td>{{ $result->client_email }}</td>
                <td>{{ $result->client_email_secondary }}</td>
                <td>{{ $result->users->name }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
