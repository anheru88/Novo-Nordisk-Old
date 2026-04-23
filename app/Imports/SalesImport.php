<?php

namespace App\Imports;

use App\SalesDetails;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SalesImport implements ToModel,WithHeadingRow,WithChunkReading,WithValidation, SkipsOnFailure
{
    use Importable;
    use SkipsFailures;


    public function __construct($id)
    {
        $this->id_sales = $id;
    }



    public function model(array $row)
    {
        return new SalesDetails([
            'id_sales'          => $this->id_sales,
            'billT'             => $row['billt'],
            'bill_number'       => $row['factura_electronica'],
            'bill_ltm'          => $row['billitm'],
            'prod_sap_code'     => $row['material'],
            'client_sap_code'   => $row['sold_to_pt'],
            'po_number'         => $row['po_number'],
            'payterm_code'      => $row['payt'],
            'brand'             => $row['brand'],
            'bill_doc'          => $row['billdoc'],
            'bill_date'         => $row['bill_date'],
            'bill_quanty'       => $row['billed_qty'],
            'bill_price'        => $row['price'],
            'bill_net_value'    => $row['net_value'],
            'bill_real_qty'     => $row['real_qty'],
            'unitxmaterial'     => $row['unit_per_material'],
            'volume'            => $row['volume'],
            'value_mdkk'        => $row['value_mdkk'],
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function rules(): array
    {
        return [
            'billt' => 'required',
            'factura_electronica' => 'required',
            'billitm' => 'required',
            'material' => 'required',
            'sold_to_pt' => 'required',
            'po_number' => 'required',
            'payt' => 'required',
            'brand' => 'required',
            'billdoc' => 'required',
            'bill_date' => 'required',
            'billed_qty' => 'required',
            'price' => 'required',
            'net_value' => 'required',
            'real_qty' => 'required',
            'unit_per_material' => 'required',
            'volume' => 'required',
            'value_mdkk' => 'required',
        ];}
}

