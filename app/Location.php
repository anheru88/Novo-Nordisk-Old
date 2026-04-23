<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    //Tabla a negociar

    protected $table = 'nvn_locations';

    protected $primaryKey = 'id_locations';

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = ['loc_codedep', 'loc_name', 'loc_codecity'];


    public function quota_location(){
        return $this->hasMany(Quotation::class,'id_city');
    }

    public function clients(){
        return $this->hasMany(Clients::class);
    }

    public static function getCities($id_department)
    {
        $codedep = Location::where('id_locations',$id_department)->first();
        $dep = \DB::table('nvn_locations')
        ->select('loc_codedep','loc_name','id_locations')
        ->where('loc_codecity', '=', $codedep->loc_codedep)
        ->orderBy('loc_name', 'ASC')
        ->get();

       // dd($dep);

        return $dep;
    }

    public static function getDepartments(){
        $dep = \DB::table('nvn_locations')
        ->select('loc_codedep','loc_name','id_locations')
        ->where('loc_codecity', '=', 0)
        ->orderBy('loc_name', 'ASC')
        ->get();

        return $dep;
    }
}
