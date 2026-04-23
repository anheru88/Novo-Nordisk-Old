<table id="datatable_full" class="table table-hover table-responsive">
    <thead>
        <tr>
            <th>CANAL</th>
            <th>CAM</th>
            <th>CAM ACTUAL</th>
            <th>Código cliente</th>
            <th>Cliente</th>
            <th>Brand</th>
            <th>Material</th>
            <th>Material Description</th>
            <th>Precio</th>
            <th>Venta</th>
            <th>Real QTY</th>
            <th>Volume</th>
            <th>% {{ $nc }}</th>
            <th>Valor {{ $nc }}</th>
            <th>NC Individual</th>
            <th>Factura</th>
            <th>Concepto</th>
            <th>Descripción</th>
            <th>Negociación</th>
            <th>ID_neg</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($results as $key => $result)
        <tr>
            <td>{{ $result['channel'] }}</td> {{-- CANAL --}}
            <td>{{ $result['cam'] }}</td> {{-- CAM --}}
            <td>{{ $result['currentcam'] }}</td> {{-- CURRENT CAM --}}
            <td>{{ $result['client_sap_code'] }}</td> {{-- Codigo cliente --}}
            <td>{{ $result['client_name'] }}</td> {{-- Nombre Cliente --}}
            <td>{{ $result['brand_name'] }}</td> {{-- Brand --}}
            <td>{{ $result['prod_sap_code'] }}</td> {{-- Material --}}
            <td>{{ $result['prod_name'] }}</td> {{-- Material Description --}}
            <td>{{ $result['prodPrice'] }}</td> {{-- Precio --}}
            <td>{{ $result['venta'] }} </td> {{-- Venta --}}
            <td>{{ $result['real_qty'] }}</td> {{-- Real QTY --}}
            <td>{{ $result['volume'] }}</td> {{-- Volume --}}
            <td>{{ $result['percent'] / 100 }}</td> {{-- % NC --}}
            <td>{{ $result['valor_nc_escala'] }}</td> {{-- NC ESCALAS --}}
            <td>{{ $result['nc_individual'] }}</td> {{-- NC Individual --}}
            <td>{{ $result['bill_number'] }}</td> {{-- Factura --}}
            <td>{{ $result['scale_name'] }}</td> {{-- Concepto --}}
            <td>{{ $result['description'] }}</td> {{-- Descripción --}}
            <td>{{ $result['consecutive'] }}</td> {{-- Negociacion --}}
            <td>{{ $result['idNegProd'] }}</td> {{-- ID_Negociacion --}}
        </tr>
        @endforeach
    </tbody>
</table>
