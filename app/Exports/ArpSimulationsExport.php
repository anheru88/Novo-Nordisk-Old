<?php

namespace App\Exports;

use App\ArpService;
use App\ArpSimulationDetail;
use App\BusinessArp;
use App\Client;
use App\NegotiationDetails;
use App\PricesList;
use App\Product;
use App\ProductxPrices;
use App\QuotationDetails;
use App\ServiceArp;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ArpSimulationsExport implements WithMultipleSheets
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($idSimulation, $brands, $arp)
    {
        $this->idSimulation     = $idSimulation;
        $this->brands           = $brands;
        $this->arp              = $arp;
    }

    public function sheets(): array
    {
        $products = [];
        $sheets = [];
        $arp = $this->arp;
        $sheetsColor = ['008080','005ad2','cccc00','ff0066','002060','92d050','00b0f0','3c5a65','543c65','008080','005ad2','cccc00','ff0066','002060','008080','005ad2','cccc00','ff0066','002060','92d050','00b0f0','3c5a65','543c65','008080','005ad2','cccc00','ff0066','002060'];

        foreach ($this->brands as $key => $brand) {
            foreach ($brand->products as $key => $product) {
                $arpSum = ArpSimulationDetail::sumDetails($product->id_product);
            }

            $priceListProducts  = $this->getLastNegotiatedPrice($arpSum, $product->id_product);
            $escalas            = $this->getEscalasDiscount($arpSum, $product->id_product);
            $info               = $this->getInfoDiscount($arpSum, $product->id_product);
            $financiero         = $this->getFinancieroDiscount($arpSum);
            $products           = $this->buildProduct($arpSum);
            $services           = $this->buildServices($arp, $brand->id_brand);
            $pbc                = BusinessArp::where('arp_id',$this->arp->services[0]->id)->where('brand_id',$brand->id_brand)->pluck('pbc')->first();
            $sheets[]           = new ArpSimulationsSheets($brand->brand_name, $sheetsColor[$key], $products, $arp, $services, $pbc, $priceListProducts, $escalas, $info, $financiero);
        }

        $name =  "Análisis versus PBC AB RE2";
        $sheets[] = new ArpVersionSheets($name);

        $name =  "TD Des Especiales";
        $sheets[] = new ArpSpecialSheet($name);

        return $sheets;
    }

    public function getLastNegotiatedPrice($arpSum, $idProduct)
    {
        $prices = [];
        $priceList  = PricesList::where('active',1)->orderBy('id_pricelists','asc')->first();
        foreach ($arpSum as $key => $arpProduct) {
            $channel = Client::clientChannel($arpProduct->client_id);
            $priceProduct = QuotationDetails::where('id_client',$arpProduct->client_id)
            ->where('id_product',$idProduct)
            ->where('is_valid',1)
            ->orWhere('is_valid',0)
            ->whereNotNull('totalValue')
            ->first();
            array_push($prices,$priceProduct->totalValue);
            //Traería el precio de la lista de precios
            /*
            $priceProduct  = ProductxPrices::where('id_product',$idProduct)
            ->where('id_pricelists',$priceList->id_pricelists)
            ->first();

            if($channel == 6){
                array_push($prices,$priceProduct->v_commercial_price);
            }else{
                array_push($prices,$priceProduct->v_institutional_price);
            }
            */
        }
        return $prices;
    }

    public function getEscalasDiscount($arpSum, $idProduct)
    {
        $escalas = [];
        foreach ($arpSum as $key => $arpProduct) {
            $prodNegotiation = NegotiationDetails::where('id_client',$arpProduct->client_id)
            ->where('id_product',$idProduct)
            ->where('id_concept',0)
            ->with('negotiation','client','product')
            ->first();

            if (isset($prodNegotiation)) {
                array_push($escalas,$prodNegotiation->discount);
            }
        }
        return $escalas;
    }

    public function getInfoDiscount($arpSum, $idProduct)
    {
        $info = [];
        foreach ($arpSum as $key => $arpProduct) {
            $prodNegotiation = NegotiationDetails::where('id_client',$arpProduct->client_id)
            ->where('id_product',$idProduct)
            ->where('is_valid',1)
            ->orWhere('is_valid',14)
            ->where('id_concept',1)
            ->first();
            array_push($info,$prodNegotiation->discount);
        }
        return $info;
    }

    public function getFinancieroDiscount($arpSum)
    {
        $financiero = [];
        foreach ($arpSum as $key => $arpProduct) {
            $client = Client::where('id_client',$arpProduct->client_id)->with('payterm')->first();
            array_push($financiero,$client->payterm->payterm_percent);
        }
        return $financiero;
    }

    public function buildProduct($arpSum)
    {
        $products = [];
        foreach ($arpSum as $key => $arpProduct) {
            $client = Client::where('id_client', $arpProduct->client_id)->with('channel','sapCodes')->first();
            $product = Product::where('id_product',$arpProduct->product_id)->with('sapCodes')->first();
            $response = [];
            $response["id"]                 = $product->id_product;
            $response["channel"]            = $client->channel->channel_name;
            $response["client_number"]      = $client->client_sap_code;
            $response["client_id"]          = $client->id_client;
            $response["client"]             = $client->client_name;
            $response["material"]           = $product->sapCodes[0]->sap_code;
            $response["material_name"]      = $product->prod_name;
            $response["volume"]             = $arpProduct->volume;
            $response["value_cop"]          = ceil($arpProduct->valuecop * 1000000);
            array_push($products, $response);
        }

        return $products;
    }

    public function buildServices($arp, $brandId)
    {
        $services = ServiceArp::where('arps_id', $arp->id)->get();
        $servicesArray = [];

        foreach ($services as $key => $service) {
            $dataService = ArpService::where('brand_id', $brandId)->where('services_arp_id',$service->id)->first();
            $response = [];
            $response["name"]               = $service->name;
            $response["volume"]             = $dataService->volume;
            $response["value_cop"]          = $dataService->value_cop;
            array_push($servicesArray, $response);
        }

        return $servicesArray;
    }

}
