@php
$finalRow = $endProd + 1;
@endphp
<table>
    <tbody>
        <tr> <!-- 1 fila -->
            <td></td>
            <td>std.{{ $arp->year }}</td>
            <td>{{ $arp->std }}</td>
        </tr>
        <tr> <!-- 2 fila -->
            <td></td>
            @foreach ($services as $service)
            <td>{{ $service['name'] }} Vol </td>
            <td>{{ $service['volume']}}</td>
            @endforeach
        </tr>
        <tr> <!-- 3 fila -->
            <td></td>
            @foreach ($services as $service)
            <td>{{ $service['name'] }} Val. DKK </td>
            <td>{{ $service['value_cop']}}</td>
            @endforeach
        </tr>
        <tr> <!-- 4 fila -->
            <td></td>
            @foreach ($services as $key => $service)
            @php
                if($key == 0){
                    $i = 2;
                } else{
                    $i = $i + 2;
                }
            @endphp
            <td>{{ $service['name'] }} Val. COP</td>
            <td>={{ $cols[$i] }}3/C1</td>
            @endforeach
        </tr>
        <tr> <!-- 5 fila -->
            <td></td>
            @foreach ($services as $key => $service)
            @php
                if($key == 0){
                    $i = 2;
                } else{
                    $i = $i + 2;
                }
            @endphp
            <td>REA Vol.</td>
            <td>{{ "=G$finalRow" }}</td>
            @endforeach
        </tr>
        <tr> <!-- 6 fila -->
            <td></td>
            @foreach ($services as $key => $service)
            @php
                if($key == 0){
                    $i = 2;
                } else{
                    $i = $i + 2;
                }
            @endphp
            <td>YTG Vol.</td>
            <td>={{ $cols[$i] }}2-{{ $cols[$i] }}5</td>
            @endforeach
        </tr>
        <tr> <!-- 7 fila -->
            <td></td>
            @foreach ($services as $key => $service)
            @php
                if($key == 0){
                    $i = 2;
                } else{
                    $i = $i + 2;
                }
            @endphp
            <td>Total</td>
            <td>={{ $cols[$i] }}5+{{ $cols[$i] }}6</td>
            @php
                $lastcolServices = $cols[$i];
            @endphp
            @endforeach
        </tr>
        <tr> <!-- 8 fila -->
            <td></td>
        </tr>
        <tr> <!-- 9 fila -->
            <td></td>
            <td>ARP PBC</td>
            <td>{{ $pbc }}</td>
        </tr>
        @foreach ($services as $key =>  $service)
        @php
        if($key == 0){
            $i = 2;
        } else{
            $i = $i + 2;
        }
    @endphp
        <tr> <!-- La cantidad de filas depende de la cantidad de ARPS creadas en la configuración - 10 fila -->
            <td></td>
            <td>ARP {{ $service['name'] }}</td>
            <td>={{ $cols[$i] }}4/{{ $cols[$i] }}2</td>
        </tr>
        @endforeach
        <tr>
            <td></td>
            <td>Estimated ARP</td>
            <td>=(H{{ $finalRow }}+T{{ $finalRow}})/{{ $lastcolServices }}7</td>
            <td>=C{{ $startProd - 5 }}/C{{ $startProd - 6 - $servicesSize }}-1</td>
            <td>Vrs PBC</td>
            <td>=(C{{ $startProd - 5 }}-C{{ $startProd - 6 - $servicesSize }})*C7</td>
        </tr>
        @foreach ($services as $key => $service)
            @php
                if($key == 0){
                    $i = 2;
                } else{
                    $i = $i + 2;
                }
            @endphp
        <tr>
            <td></td>
            @if ($key == 0)
            <td>REA YTD</td>
            <td>{{'=H'.$finalRow.'/G'.$finalRow}}</td>
            <td>=C{{ $startProd - 5 }}/C{{ $startProd - 8 }}-1</td>
            <td>Vrs {{ $service['name'] }}</td>
            <td>=(C{{ $startProd - 5 }}-C{{ $startProd - 8 }})*{{ $cols[$i] }}7</td>
            @else
            <td></td>
            <td></td>
            <td>=C{{ $startProd - 5 }}/C{{ $startProd - 6 }}-1</td>
            <td>Vrs {{ $service['name'] }}</td>
            <td>=(C{{ $startProd - 5 }}-C{{ $startProd - 6 }})*{{ $cols[$i] }}7</td>
            @endif
        </tr>
        @endforeach
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align: center" colspan="6">Descuentos</td>
        </tr>
        <tr>
            <th></th>
            <th>Channel</th>
            <th>Client number</th>
            <th>Client</th>
            <th>Material</th>
            <th>Material name</th>
            <th>Vol. YTD Sept</th>
            <th>Val. YTD</th>
            <th>Share</th>
            <th>Vol. YTG {{ $arp->year }}</th>
            <th>Month Avg</th>
            <th>List price</th>
            <th>Escala</th>
            <th>Info</th>
            <th>Financiero</th>
            <th>Otros</th>
            <th>Neg Especiales</th>
            <th>Total</th>
            <th>Net Price</th>
            <th>Net Sales</th>
        </tr>
        @foreach ($products as $key => $product)
        @php
        $currentRow = $startProd + $key;
        $monthAvg   = $product['volume'] / $arp->month_avr;
        @endphp
        <tr>
            <td></td>
            <td>{{ $product['channel'] }}</td>
            <td>{{ $product['client_number'] }}</td>
            <td>{{ $product['client'] }}</td>
            <td>{{ $product['material'] }}</td>
            <td>{{ $product['material_name'] }}</td>
            <td>{{ $product['volume'] }}</td><!-- G -->
            <td>{{ $product['value_cop'] }}</td> <!-- H -->
            <td>{{'=G'.$currentRow.'/$G$'.$finalRow }}</td><!-- I -->
            <td>{{'=I'.$currentRow.'*$'.$lastcolServices.'$6'}}</td> <!-- J -->
            @if ($arp->month_avr < 4)
            <td>{{ $arp->month_avr }}</td> <!-- K -->
            @else
            <td>{{'=G'.$currentRow.'/'. ($arp->month_avr - 1) }}</td> <!-- K -->
            @endif
            <td>{{ $priceListProducts[$key] }}</td> <!-- L -->
            <td>{{ getScale($monthAvg, $product['id'], $product["client_id"]) }}</td> <!-- M -->
            <td>{{ $info[$key] }}</td> <!-- N -->
            <td>{{ $financiero[$key] }}</td> <!-- O -->
            <td></td> <!-- P -->
            <td>{!! "SI.ERROR(BUSCARV(A22;'TD Des Especiales'!$"."A$".$currentRow.":$"."B$144;2;0);0%)" !!}</td> <!-- Q -->
            <td>{!! "=SUM(M$currentRow:Q$currentRow)" !!}</td> <!-- R -->
            <td>{{ '=L'.$currentRow. '*(1-R'.$currentRow.')' }}</td> <!-- S -->
            <td>{!! "=IF(J$currentRow>0,J$currentRow*S$currentRow,0)" !!}</td> <!-- T -->
        </tr>
        @endforeach
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{!! "=SUM(G$startProd:G$endProd)" !!}</td> <!-- G -->
            <td>{!! "=SUM(H$startProd:H$endProd)" !!}</td> <!-- H -->
            <td>{!! "=SUM(I$startProd:I$endProd)" !!}</td> <!-- I -->
            <td>{!! "=SUM(J$startProd:J$endProd)" !!}</td> <!-- J -->
            <td>{!! "=SUM(K$startProd:K$endProd)" !!}</td> <!-- K -->
            <td></td> <!-- L -->
            <td></td> <!-- M -->
            <td></td> <!-- N -->
            <td></td> <!-- O -->
            <td></td> <!-- P -->
            <td></td> <!-- Q -->
            <td></td> <!-- R -->
            <td></td> <!-- S -->
            <td>{!! "=SUM(T$startProd:T$endProd)" !!}</td> <!-- T -->
        </tr>
    </tbody>
</table>
