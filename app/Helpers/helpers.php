<?php

use App\ClientxProductScale;
use App\Http\Controllers\NegotiationsController;
use App\Http\Controllers\QuotationsController;
use App\MeasureUnit;
use App\NegotiationConcepts;
use App\PaymentTerms;
use App\PricesList;
use App\Product;
use App\Quotation;
use App\Scales;
use App\ScalesLevels;
use App\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToArray;

/**
 * Return nav-here if current path begins with this path.
 *
 * @param string $path
 * @return string
 */
function setActive($path)
{
    return Request::is($path . '*') ? 'active' :  '';
}

function getRole($rol)
{
    $role = Role::find($rol);
    return $role->getPermissions();
}

function getCount($type)
{
    $counter = QuotationsController::getCount($type);
    return $counter;
}

function getCountAllQuota(){
    $counter = QuotationsController::getCountAllQuota();
    return $counter;
}

function getCountPre()
{
    $counterQuotations = QuotationsController::getCountPre();
    $counterNegotiation = NegotiationsController::getCountPre();
    $counter = $counterQuotations + $counterNegotiation;
    return $counter;
}

function getCountAuth()
{
    $counterQ = QuotationsController::getCountAutorizaciones();
    $counterN = NegotiationsController::getCountAutorizaciones();
    $counterL = PricesList::where('id_authorizer_user', Auth::user()->id)->where('active', 0)->count();
    $counter  = $counterQ + $counterN + $counterL;
    return $counter;
}

function getCotTitle($estatus)
{

    $res = "";
    switch ($estatus) {
        case '1':
            $res = 'Pendientes';
            break;
        case '2':
            $res = 'Finalizadas';
            break;
        case '3':
            $res = 'Anuladas';
            break;
        case '4':
            $res = 'Aprobadas';
            break;
        case '5':
            $res = 'Rechazadas';
            break;
        default:
            $res = 'Totales';
            break;
    }

    return $res;
}

function statusList($estatus)
{
    switch ($estatus) {
        case '0':
            $res = '<span class="label label-warning"><i class="glyphicon glyphicon-question-sign"></i> Pendiente</span>';
            break;
        case '1':
            $res = '<span class="label label-success"><i class="glyphicon  glyphicon-ok"></i> Aprobado</span>';
            break;
        case '2':
            $res = '<span class="label label-danger"><i class="fas fa-times"></i> Rechazado</span>';
            break;
    }
    return $res;
}

function statusCot($estatus, $pre_aproved)
{
    $res = "";

    if ($estatus == 1 && $pre_aproved == 0) {
        $res = '<span class="label label-warning"><i class="glyphicon glyphicon-question-sign"></i> Pendiente</span>';
    }

    if ($estatus == 1 && $pre_aproved == 1) {
        $res = '<span class="label label-info"><i class="glyphicon glyphicon-question-sign"></i> Pre-aprobada</span>';
    }

    if ($estatus == 4 && $pre_aproved == 1 || $estatus == 4 && $pre_aproved == 0) {
        $res = '<span class="label label-success"><i class="glyphicon  glyphicon-ok"></i> Aprobada</span>';
    }

    if ($estatus == 5 && $pre_aproved == 0 || $estatus == 5 && $pre_aproved == 1) {
        $res = '<span class="label label-danger"><i class="fas fa-times"></i> Rechazada</span>';
    }

    if ($estatus == 6) {
        $res = '<span class="label label-danger"><i class="fas fa-times"></i> Anulada</span>';
    }

    if ($estatus == 7) {
        $res = '<span class="label label-danger"><i class="far fa-calendar-times"></i> Vencida</span>';
    }

    return $res;
}

function statushelp($estatus, $pre_aproved, $status_id)
{
    $res = "";

    if ($status_id == 6 ) {

        $res = 'Aprobada';
    }else{

        if ($estatus == 1 && $pre_aproved == 0) {
            $res = 'Pendiente';
        }

        if ($estatus == 1 && $pre_aproved == 1) {
            $res = 'Pre-aprobada';
        }

        if ($estatus == 4 && $pre_aproved == 1 || $estatus == 4 && $pre_aproved == 0) {
            $res = 'Aprobada';
        }

        if ($estatus == 5 && $pre_aproved == 0 || $estatus == 5 && $pre_aproved == 1) {
            $res = 'Rechazada';
        }

        if ($estatus == 6) {
            $res = 'Anulada';
        }

        if ($estatus == 7) {
            $res = 'Vencida';
        }

        if($estatus >= 7){
            $res = 'Vencida';
        }

    }

    return $res;
}

function scalename($prodid)
{
    $scalename = Scales::where('id_product', $prodid)->first(['scale_number']);
    if (isset($scalename)) {
        $scalename = $scalename->scale_number;
    }elseif(empty($scalename) || $scalename == null) {
        $scalename = 'N/A';
    }
    // dd($scalename);
    return $scalename;
}

function clarification($aclaracion)
{
    if (isset($aclaracion)) {
        $aclaracion;
    }elseif(empty($aclaracion) || $aclaracion == null) {
        $aclaracion = 'N/A';
    }
    // dd($aclaracion);
    return $aclaracion;
}

function conceptname($conceptid)
{
    if ($conceptid > 0 && $conceptid != null) {
        $conceptname = NegotiationConcepts::where('id_negotiation_concepts', $conceptid)->first();
        // dd($conceptid);
        if ($conceptname == null) {
            $conceptname = 'Escala';
        } else {
            $conceptname = $conceptname->name_concept;
        }
    } elseif ($conceptid <= 0) {
        $conceptname = 'Escala';
    }
    return $conceptname;
}

function statusNeg($estatus, $pre_approved)
{

    $res = "";

    if ($estatus == 1 && $pre_approved == 0) {
        $res = '<span class="label label-warning"><i class="glyphicon glyphicon-question-sign"></i> Pendiente</span>';
    }

    if ($estatus == 1 && $pre_approved == 1) {
        $res = '<span class="label label-info"><i class="glyphicon glyphicon-question-sign"></i> Pre-aprobación 1</span>';
    }

    if ($estatus == 2 && $pre_approved == 1) {
        $res = '<span class="label label-info bg-orange-active color-palette"><i class="glyphicon glyphicon-question-sign"></i> Pre-aprobación 2</span>';
    }

    if ($estatus == 4 && $pre_approved == 1 || $estatus == 4 && $pre_approved == 0) {
        $res = '<span class="label label-success"><i class="glyphicon  glyphicon-ok"></i> Aprobada</span>';
    }

    if ($estatus == 5 && $pre_approved == 0 || $estatus == 5 && $pre_approved == 1) {
        $res = '<span class="label label-danger"><i class="fas fa-times"></i> Rechazada</span>';
    }

    if ($estatus == 6) {
        $res = '<span class="label label-danger"><i class="fas fa-times"></i> Anulada</span>';
    }

    if ($estatus == 7) {
        $res = '<span class="label label-darkred"><i class="far fa-calendar-times"></i> Vencida</span>';
    }


    return $res;
}

function statusCli($estatus)
{
    $res = '';
    switch ($estatus) {
        case '1':
            $res = '<span class="label label-success"><i class="glyphicon  glyphicon-ok"></i> activo</span>';
            break;
        case '0':
            $res = '<span class="label label-danger"><i class="fas fa-times"></i> inactivo</span>';
            break;
    }
    return $res;
}

function statusExcelCli($estatus)
{
    switch ($estatus) {
        case '1':
            $res = 'activo';
            break;
        case '0':
            $res = 'inactivo';
            break;
    }
    return $res;
}

function paytermName($id_payterm, $payterm_name)
{
    if (!empty($id_payterm)) {
        $payterm = $payterm_name;
    } else {
        $payterm = '-.-';
    }
    return $payterm;
}

function clientActive($active)
{
    if ($active == 0) {
        $res = 'Inactivo';
    } else {
        $res = 'Activo';
    }
    return $res;
}

function getPaytermName($id_payterm)
{
    $name =  PaymentTerms::where('id_payterms', $id_payterm)->first();
    return $name->payterm_name;
}

function getPaytermCode($id_payterm)
{
    $name =  PaymentTerms::where('id_payterms', $id_payterm)->first();
    return $name->payterm_code;
}

function getPaytermPercent($id_payterm)
{
    $name =  PaymentTerms::where('id_payterms', $id_payterm)->first();
    return $name->payterm_percent;
}

function getProductInfo($id_prod)
{
    $prod = Product::where('id_product', $id_prod)->first();
    return $prod;
}

function getProductScales($id_prod)
{
    $scales = Scales::where('id_product', $id_prod)->pluck('scale_number', 'id_scale');
    return $scales;
}


function getProductxClientScales($id_prod, $id_client)
{
    $scaleID = ClientxProductScale::where('id_product', $id_prod)->where('id_client', $id_client)->pluck('id_scale');
    if (sizeof($scaleID) > 0) {
        return $scaleID;
    } else {
        return null;
    }
}

function quitar_tildes($cadena)
{
    $no_permitidas = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "Ã‘", "Ã±");
    $permitidas = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "ñ", "ñ", "ñ");
    $texto = str_replace($no_permitidas, $permitidas, $cadena);
    return $texto;
}

function getQuotaConsecutive($idQuota)
{

    $consec = Quotation::where('id_quotation', $idQuota)->first();
    return $consec->quota_consecutive;
}

function getNickName($idUser){

    $userNick = User::where('id',$idUser)->first();
    if ($userNick) {
        return $userNick->nickname;
    }else{
        return "No aplica";
    }


}

function measureUnit($id){

    $query = MeasureUnit::where('id_unit', $id)->first();
    return $query->unit_name;

}

function getScale($month_avg, $id_prod, $id_client)
{
    $scaleID = ClientxProductScale::where('id_product', $id_prod)->where('id_client', $id_client)->pluck('id_scale');

    if(sizeof($scaleID) > 0){
        $prod = Product::find($id_prod);
        if($prod->arp_divide){
            $month_avg = $month_avg / $prod->prod_package_unit;
        }
        $scaleLvl = ScalesLevels::where('id_scale',$scaleID[0])->where('scale_min','<',$month_avg)->where('scale_max','>',$month_avg)->first();
        if(isset($scaleLvl)){
            return $scaleLvl->scale_discount;
        }else{
            return 0;
        }
    }else{
        return "";
    }

}
