<?php

namespace App\Imports;

use App\MeasureUnit;
use App\Product;
use App\Product_Line;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel,WithHeadingRow
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $linea = Product_Line::whereRaw("UPPER(line_name) LIKE '%".strtoupper($row['linea'])."%'")->first();
        if(!empty($linea)){
            $id_prod_line = $linea->id_line;
        }else{
            $id_prod_line = null;
        }
        


        $unidad = MeasureUnit::whereRaw("UPPER(unit_name) LIKE '%".strtoupper($row['venta_por_unidad_minima'])."%'")->first();
        if(!empty($unidad)){
            $id_measure_unit = $unidad->id_unit;
        }else{
            $id_measure_unit = null;
        }


        if($row['tope_maximo_incremento'] == "N/A"){
            $tope = $row['tope_maximo_incremento'];
        }else{
            $tope =  ceil($row['tope_maximo_incremento']);
        }



        return new Product([
            'prod_name'                 => $row['producto'],
            'prod_commercial_name'      => $row['nombre_comercial'],
            'prod_generic_name'         => $row['nombre_generico'],
            'prod_sap_code'             => $row['codigo_sap'],
            'id_prod_line'              => $id_prod_line,
            'prod_invima_reg'           => $row['registro_invima'],
            'prod_cum'                  => $row['codigo_cum'],
            'prod_package'              => $row['presentacion'],
            'prod_package_unit'         => $row['unidades_de_presentacion'],
            'id_measure_unit'           => $id_measure_unit,
            'is_prod_regulated'         => strtoupper($row['medicamento_en_control']),
            'v_institutional_price'     => ceil($row['precio_institucional']),
            'v_commercial_price'        => ceil($row['precio_comercial']),
            'prod_valid_date_ini'       => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['vigencia_desde']),
            'prod_valid_date_end'       => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['vigencia_hasta']),
            'prod_increment_max'        => $tope,
            'created_by'                => Auth::user()->id
        ]);
    }
}
