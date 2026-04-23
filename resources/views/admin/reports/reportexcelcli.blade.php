<table id="datatable_full" class="table table-hover table-responsive">
    <thead>
        <tr>
            <th># cliente</th>
            <th>Nombre cliente</th>
            <th>Client NIT</th>
            <th>Canal</th>
            <th>Tipo de cliente</th>
            <th>Departamento</th>
            <th>Ciudad</th>
            <th>Código SAP</th>
            <th>Cupo</th>
            <th>Forma de pago</th>
            <th>Estado del cliente</th>
            {{-- contacto --}}
            <th>Nombre de contacto</th>
            <th>Cargo de contacto</th>
            <th>Dirección</th>
            <th>Teléfono</th>
            <th>Email de contacto principal</th>
            <th>Otros emails de contacto</th>
            <th>CAM</th>
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
                <td>{{ $result->payterm->payterm_name }}</td>
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
        @endfor
    </tbody>
</table>