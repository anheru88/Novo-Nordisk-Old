<?php

namespace App\Exports;

use App\Client;
use App\ClientxProductScale;
use App\Negotiation;
use App\NegotiationConcepts;
use App\NegotiationDetails;
use App\Product;
use App\Quotation;
use App\QuotationDetails;
use App\SalesDetails;
use App\Scales;
use App\ScalesLevels;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class NotesExport implements FromView, WithEvents, ShouldAutoSize, WithTitle, WithColumnFormatting
{
    //use Exportable;

    private $idScale;
    private $nameC;
    private $idSale;
    private $sales = [];

    public function __construct($ncType, $nameC, $idSale)
    {
        $this->ncType  = $ncType;
        $this->nameC    = strtoupper('NC ' . $nameC);
        $this->idSale   = $idSale;
    }

    public function title(): string
    {
        return  $this->nameC;
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:S1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12)->setBold(true)->getColor()->setRGB('ffffff');
                $event->sheet->getDelegate()->getStyle($cellRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('44546a');
                $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setHorizontal('center');
            }
        ];
    }

    public function columnFormats(): array
    {
        return [
            'I' => NumberFormat::FORMAT_CURRENCY_USD_INTEGER,
            'J' => NumberFormat::FORMAT_CURRENCY_USD_INTEGER,
            'M' => NumberFormat::FORMAT_PERCENTAGE,
            'N' => NumberFormat::FORMAT_CURRENCY_USD_INTEGER,
            'O' => NumberFormat::FORMAT_CURRENCY_USD_INTEGER,
        ];
    }


    public function view(): View
    {
        $nameC = $this->nameC;
        /*$sales = SalesDetails::where('id_sales',$this->idSale)->where('bill_price', '>', 0)->get();
        dd($sales);*/
        $sales = SalesDetails::select([
            'nvn_sales_details.client_sap_code',
            'nvn_clients.client_name',
            'nvn_brands.brand_name',
            'nvn_sales_details.prod_sap_code',
            DB::raw("SUM(nvn_sales_details.bill_real_qty) as real_qty"),
            DB::raw("SUM(nvn_sales_details.bill_price) as precio"),
            DB::raw("SUM(nvn_sales_details.bill_net_value) as venta"),
            DB::raw("SUM(nvn_sales_details.volume) as volume"),
            DB::raw("COUNT(nvn_sales_details.bill_price) as precio_count"),
            'nvn_products.id_product',
            'nvn_products.prod_name',
            'nvn_clients.id_client',
        ])
            ->join('nvn_products', 'nvn_products.prod_sap_code', '=', 'nvn_sales_details.prod_sap_code')
            ->join('nvn_brands', 'nvn_brands.id_brand', '=', 'nvn_products.id_brand')
            ->join('nvn_clients', 'nvn_clients.client_sap_code', '=', 'nvn_sales_details.client_sap_code')
            ->groupBy(
                'nvn_sales_details.prod_sap_code',
                'nvn_sales_details.client_sap_code',
                'nvn_clients.client_name',
                'nvn_brands.brand_name',
                'nvn_products.id_product',
                'nvn_clients.id_client',
                'nvn_products.prod_name'
            )
            ->orderBy('nvn_clients.client_name', 'ASC')
            ->where('nvn_sales_details.bill_price', '>', 0)
            ->where('nvn_sales_details.id_sales', $this->idSale)
            //->whereRaw('CAST(nvn_product_scales_level.scale_min as INTEGER) <= cantidad')
            ->get();

        $ncEscalas = [];
        $percents = [];
        foreach ($sales as $key => $sale) {
            $volume = 0;
            $idProduct      = $sale->id_product;
            $idClient       = $sale->id_client;
            $qty            = $sale->real_qty;
            $venta          = $sale->venta;
            $product_unit   = Product::where('id_product', $idProduct)->pluck('prod_package_unit');
            $percent        = 0;
            $compress       = 0;

            $concept = NegotiationConcepts::where('id_negotiation_concepts', $this->ncType)->first();
            if ($concept) {
                $compress = $concept->concept_compress;
            }

            if ($this->ncType == 0 || $compress == 1) { // Exportacion de NC ESCALAS
                $negoDetail    = NegotiationDetails::where('id_product', $idProduct)
                    ->where('id_concept', $this->ncType)
                    ->where('id_client', $idClient)
                    ->whereIn('is_valid',[1,6])
                    //->where('is_valid', 1)
                    ->with('negotiation', 'quotation')->first();

                if ($negoDetail) {
                    $response = [];
                    $created_by = $negoDetail->negotiation->creator->nickname;
                    if ($this->ncType == 0) {
                        $clientProduct = ClientxProductScale::where('id_client', $idClient)->where('id_product', $idProduct)->first();
                        //  Suma de volumenes por marca
                        $volume = 0;
                        $queryvol = SalesDetails::where('client_sap_code', $sale->client_sap_code)
                            //->where('UPPER(brand)','LIKE','%'.$sale->brand_name.'%')
                            ->whereRaw('UPPER("brand") like ?', strtoupper($sale->brand_name))
                            ->where('id_sales', $this->idSale)
                            ->get();

                        foreach ($queryvol as $key => $vol) {
                            $product_unitInt = Product::where('prod_sap_code', $vol->prod_sap_code)->pluck('prod_package_unit');
                            if (sizeof($product_unitInt) > 0) {
                                $realqty_ind = intval($vol->bill_real_qty) * intval($product_unitInt[0]);
                                $volume = $volume + $realqty_ind;
                            }
                        }

                        if ($volume == 0) {
                            $volume = $sale->volume;
                        }

                        if (!$clientProduct) {
                            $percent = 0;
                        } else {

                            $idScale = $clientProduct->id_scale;
                            $clientScale = ScalesLevels::whereRaw('CAST(scale_min as INTEGER) <= ' . intval($volume))
                                ->whereHas('scale', function ($query) use ($idScale) {
                                    return $query->where('id_scale', $idScale);
                                })
                                ->with('scale')
                                ->orderBy('id_scale_level', 'DESC')
                                ->first();

                            if ($clientScale) {
                                $percent = $clientScale->scale_discount;
                            } else {
                                $percent = 0;
                            }
                        }
                    } else {
                        $percent    = $negoDetail->discount;
                        $realqty    = $qty;
                    }

                    $bill = SalesDetails::where('prod_sap_code', $sale->prod_sap_code)
                        ->where('client_sap_code', $sale->client_sap_code)
                        ->where('id_sales', $this->idSale)
                        ->orderBy('bill_net_value', 'DESC')
                        ->first();

                   // dd($bill);
                    $quotationActive = Quotation::where('id_quotation', $negoDetail->id_quotation)->where('is_authorized', 4)->orWhere('status_id', 6)->first();

                    if ($quotationActive) {
                        //dd($quotationActive);
                        $quotaDetails = QuotationDetails::where('id_quotation', $negoDetail->id_quotation)
                            ->where('id_client', $idClient)
                            ->where('id_product', $idProduct)
                            ->whereIn('is_valid', [1,6])
                            ->first();
                        $client = Client::where('id_client', $idClient)->with('channel', 'users')->first();
                        //dd($quotaDetails);
                        //dd($bill);

                        if (!$quotaDetails) {
                            //dd("error" . $negoDetail->id_quotation);
                            $priceProduct = "Sin vigencia";
                        } else {
                            $priceProduct = $quotaDetails->totalValue;
                        }
                        if ($volume > 0  && $percent != 0 && $sale->real_qty > 0) {
                            $valorNcEscala = $venta / (100 / $percent);
                            $ncIndividual   = $valorNcEscala / $sale->real_qty;
                        } else {
                            $valorNcEscala = "errado";
                            $ncIndividual   = "errado";
                        }

                        // volumen = 0 / qty / unit_per_material.
                        $response['client_name']        = $sale->client_name;
                        $response['brand_name']         = $sale->brand_name;
                        $response['prod_sap_code']      = $sale->prod_sap_code;
                        $response['real_qty']           = $sale->real_qty;
                        $response['venta']              = $sale->venta;
                        $response['volume']             = $sale->volume;
                        $response['precio_count']       = $sale->precio_count;
                        $response['id_product']         = $sale->id_product;
                        $response['prod_name']          = $sale->prod_name;
                        $response['id_client']          = $sale->id_client;
                        $response['client_sap_code']    = $sale->client_sap_code;
                        $response['percent']            = $percent;
                        $response['valor_nc_escala']    = $valorNcEscala;
                        $response['nc_individual']      = $ncIndividual;
                        $response['cam']                = $created_by;
                        $response['currentcam']         = $client->users->nickname;
                        $response['scale_name']         = $nameC;
                        $response['bill_number']        = $bill->bill_number;
                        $response['description']        = $negoDetail->observations;
                        $response['consecutive']        = $negoDetail->negotiation->negotiation_consecutive;
                        $response['idNegProd']          = $negoDetail->id_negotiation_det;
                        $response['channel']            = $client->channel->channel_name;
                        $response['prodPrice']          = $priceProduct;
                        if ($percent > 0 && $quotaDetails) {
                            array_push($ncEscalas, $response);
                        }
                    }
                }

            } else {
                $negoDetails = NegotiationDetails::where('id_product', $idProduct)
                    ->where('id_concept', $this->ncType)
                    ->where('id_client', $idClient)
                    ->whereIn('is_valid',[1,6])
                    //->where('is_valid', 1)
                    ->with('negotiation', 'quotation')->get();

                if ($negoDetails) {
                    $response = [];
                    foreach ($negoDetails as $negoDetail) {
                        $created_by = $negoDetail->negotiation->creator->nickname;

                        if ($this->ncType == 0) {
                            $clientProduct = ClientxProductScale::where('id_client', $idClient)->where('id_product', $idProduct)->first();
                            $idScale = $clientProduct->id_scale;
                            $clientScale = ScalesLevels::whereRaw('CAST(scale_min as INTEGER) <= ' . $qty)
                                ->whereHas('scale', function ($query) use ($idScale) {
                                    return $query->where('id_scale', $idScale);
                                })
                                ->with('scale')
                                ->orderBy('id_scale_level', 'DESC')
                                ->first();
                            if ($clientScale) {
                                $percent = $clientScale->scale_discount;
                            } else {
                                $percent = 0;
                            }
                        } else {
                            $percent = $negoDetail->discount;
                        }

                        $bill = SalesDetails::where('prod_sap_code', $sale->prod_sap_code)->where('client_sap_code', $sale->client_sap_code)->where('id_sales', $this->idSale)->orderBy('bill_net_value', 'DESC')->first();

                        $quotationActive = Quotation::where('id_quotation', $negoDetail->id_quotation)->where('is_authorized', 4)->orWhere('status_id', 6)->first();

                        if ($quotationActive) {

                            $quotaDetails = QuotationDetails::where('id_quotation', $negoDetail->id_quotation)
                                ->where('id_client', $idClient)
                                ->where('id_product', $idProduct)
                                ->whereIn('is_valid', [1,6])
                                ->first();
                            $client = Client::where('id_client', $idClient)->with('channel', 'users')->first();

                            if (!$quotaDetails) {
                                // dd("error" . $negoDetail->id_quotation);
                                $priceProduct = "Sin vigencia";
                            } else {
                                $priceProduct = $quotaDetails->totalValue;
                            }

                            if ($percent > 0 && $sale->real_qty > 0) {
                                //dd($negoDetails);
                                $valorNcEscala  = $venta * ($percent / 100);
                                $ncIndividual   = $valorNcEscala / $sale->real_qty;

                                // volumen = 0 / qty / unit_per_material.
                                $response['client_name']        = $sale->client_name;
                                $response['brand_name']         = $sale->brand_name;
                                $response['prod_sap_code']      = $sale->prod_sap_code;
                                $response['real_qty']           = $sale->real_qty;
                                $response['venta']              = $sale->venta;
                                $response['volume']             = $sale->volume;
                                $response['precio_count']       = $sale->precio_count;
                                $response['id_product']         = $sale->id_product;
                                $response['prod_name']          = $sale->prod_name;
                                $response['id_client']          = $sale->id_client;
                                $response['client_sap_code']    = $sale->client_sap_code;
                                $response['percent']            = $percent;
                                $response['valor_nc_escala']    = $valorNcEscala;
                                $response['nc_individual']      = $ncIndividual;
                                $response['cam']                = $created_by;
                                $response['currentcam']         = $client->users->nickname;
                                $response['scale_name']         = $nameC;
                                $response['bill_number']        = $bill->bill_number;
                                $response['description']        = $negoDetail->observations;
                                $response['consecutive']        = $negoDetail->negotiation->negotiation_consecutive;
                                $response['idNegProd']          = $negoDetail->id_quotation;
                                $response['channel']            = $client->channel->channel_name;
                                $response['prodPrice']          = $priceProduct;
                                if ($percent > 0) {
                                    array_push($ncEscalas, $response);
                                }
                            }
                        }
                    }
                }
            }
        }
        //dd($ncEscalas);

        return view('admin.notas.tablenotes', ['results' => $ncEscalas, 'nc' => $nameC]);
    }
}
