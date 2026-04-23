<?php

namespace App\Imports;

use App\Channel_Types;
use App\Client;
use App\Client_Types;
use App\Location;
use App\PaymentTerms;
use App\User;
use Caffeinated\Shinobi\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClientsImport implements ToModel,WithHeadingRow
{

    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {


        $payment = PaymentTerms::where('payterm_code','=',$row['codigo_condicion_financiera'])->first();
        if(!empty($payment)){
            $id_payterm = $payment->id_payterms;
        }else{
            $id_payterm = null;
        }


        $type = Client_Types::where('type_name','LIKE','%'.quitar_tildes(strtoupper($row['tipo_de_cliente'])).'%')->first();
        if(!empty($type)){
            $id_type= $type->id_type;
        }else{
            $id_type =null;
        }

        $canal = Channel_Types::where('channel_name','LIKE','%' .$row['canal']. '%')->first();
        if(!empty($canal)){
            $id_canal= $canal->id_channel;
        }else{
            $id_canal = null;
        }

        $user = User::where('nickname','LIKE','%' .$row['cam']. '%')->first();
        if(!empty($user)){
            $id_user = $user->id;
        }else{
            $id_user = Auth::user()->id;
        }

        $dep = quitar_tildes($row['departamento']);
       // $dep = utf8_decode((strtoupper($dep)));
        $dep = mb_strtoupper($dep);
        //dd(mb_strtoupper($row['departamento']));

        $departamento = Location::where('loc_name','LIKE','%'.$dep.'%')->first();
        if(!empty($departamento)){
            $id_departamento = $departamento->id_locations;
        }else{
            dd($dep);
            $id_departamento = null;
        }
       


        $ciudad = Location::where('loc_name','LIKE','%'.quitar_tildes($row['ciudad']).'%')->first();
        if(!empty($ciudad)){
            $id_ciudad = $ciudad->id_locations;
        }else{
            $id_ciudad = null;
        }
        //dd($ciudad);

        if($row['activo'] == 'SI'){
            $activo = 1;
        }else{
            $activo = 0;
        }





        return new Client([

            'id_client_type'            => $id_type,
            'client_name'               => $row['nombre_del_cliente'],
            'client_nit'                => $row['nit'],
            'client_sap_code'           => $row['codigo_sap'],
            'id_client_channel'         => $id_canal,
            'id_department'             => $id_departamento,
            'id_city'                   => $id_ciudad,
            'client_address'            => $row['direccion'],
            'client_phone'              => $row['telefono'],
            'client_email'              => $row['email_principal'],
            'client_email_secondary'    => $row['email_secundario'],
            'client_credit'             => $row['cupo'],
            'client_contact'            => $row['nombre_de_contacto'],
            'client_position'           => $row['cargo_de_contacto'],
            'id_payterm'                => $id_payterm,
            'id_diab_contact'           => $id_user,
            'created_by'                => $id_user,
            'active'                    => $activo,
            'created_at'                => Carbon::now(),
            'updated_at'                => Carbon::now(),

        ]);
    }

}
