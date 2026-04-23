<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Location;

class LocationsController extends Controller
{

    public function getCities(Request $request){
        //$tipoServicios = TipoVehiculo::all();
        $id_department = $request->department;
        $cities = Location::getCities($id_department);
        return $cities;
    }
}
