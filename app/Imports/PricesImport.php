<?php

namespace App\Imports;

use App\Product_AuthLevels;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class PricesImport implements ToModel,WithHeadingRow
{

    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Product_AuthLevels([
            //
        ]);
    }
}
