<?php

namespace App\Imports;

use App\ArpSimulationDetail;
use App\Client;
use App\Product;
use App\User;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Validators\ValidationException;

class ArpImport implements ToModel,WithHeadingRow,WithChunkReading
{
    use Importable;
    private $rows = 0;

    public function __construct($id)
    {
        $this->arp_id = $id;
    }

    public function model(array $row)
    {
        $currentRowNumber = ++$this->rows;
        $sap_product = $row['material'];
        $sap_client  = $row['client_number'];
        $cam         = $row['commercial'];
        $year_month  = explode(',',$row['cal_year_month']);
        $year_month  = $year_month[0].'-'.$year_month[1];

        $product = Product::where('prod_sap_code',$sap_product)
        ->orWhereHas('sapCodes', function ($query) use($sap_product) {
            return $query->where('sap_code', $sap_product);
        })->first();

        $client  = Client::where('client_sap_code',$sap_client)
        ->orWhereHas('sapCodes', function ($query) use($sap_client) {
            return $query->where('sap_code', $sap_client);
        })->first();

        $cam = User::where('nickname',$cam)->first();

        if(!$cam){
            dd ($currentRowNumber);
        }

        if(!$client){
            return null;
        }

        if(!$product){
           // dd($currentRowNumber);
            $error      = ['No se encontró el producto'];
            $failures[] = new Failure($currentRowNumber, 'No encuentra el producto', $error, $row);
            throw new ValidationException(\Illuminate\Validation\ValidationException::withMessages($error), $failures);
        }

        return new ArpSimulationDetail([
            'arp_simulation_id'      => $this->arp_id,
            'brand_id'              => $product->id_brand,
            'product_id'            => $product->id_product,
            'client_id'             => $client->id_client,
            'cal_year_month'        => $year_month,
            'vol_type'              => $row['vol_type'],
            'forecast_vol'          => $row['forecast_vol'],
            'sales_pack_vol'        => $row['sales_pack_vol'],
            'volume'                => $row['volume'],
            'asp_cop'               => $row['asp_cop'],
            'amount_mcop'           => $row['amount_mcop'],
            'amount_dkk'            => $row["amount_dkk"],
            'currency'              => $row['currency'],
            'net_sales'             => $row['net_sales'],
            'version'               => $row['version'],
            'versen'                => $row['versen'],
            'year'                  => $row['year'],
            'quarter'               => $row['quarter'],
            'month'                 => $row['month'],
            'cam_id'                => $cam->id,
            'cam_status'            => $row['status'],
            'consumption_data'      => $row['consumption_data'],
            'bu'                    => $row['bu'],
            'group'                 => $row['group'],
            'cluster'               => $row['cluster'],
            'region'                => $row['region'],
        ]);

    }

    public function chunkSize(): int
    {
        return 500;
    }
}
