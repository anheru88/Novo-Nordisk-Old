<?php

namespace App\Exports;

use App\Product;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProductsExport implements FromCollection, WithHeadings
{

    public function headings(): array {
        return [
            'Producto',
            'Nombre Generico',
            'Linea',
            'Presentación',
            'Vigente',
            'Vigencia (hasta)',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $productos = Product::orderBy('id_product','DESC')->with('measureUnit','productLine','aditionalUse')->get();
        $finded = [];
        foreach ($productos as $key => $producto) {
            $finded[$key]['prod_name'] = $producto->prod_name;
            $finded[$key]['prod_generic_name'] = $producto->prod_generic_name;
            $finded[$key]['line_name'] = $producto->productLine->line_name;
            $finded[$key]['prod_package'] = $producto->prod_package;
            $product_date = date('Y-m-d',strtotime($producto->prod_valid_date_end));
            $today = date('Y-m-d');
            if($product_date >= $today){
                $vigencia = "SI";
                $vencida = date('d-m-Y',strtotime($producto->prod_valid_date_end));
                $finded[$key]['vigencia'] = $vigencia;
                $finded[$key]['vencida'] = $vencida;
            }else{
                $vigencia = "NO";
                $vencida = "red-table";
                $finded[$key]['vigencia'] = $vigencia;
                $finded[$key]['vencida'] = $vencida;
            }
        }
        return new Collection([$finded]);
    }
}
