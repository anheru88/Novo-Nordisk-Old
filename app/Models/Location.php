<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    public $timestamps = false;
    // Tabla a negociar

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'loc_codedep',
        'loc_name',
        'loc_codecity',
    ];

    public static function getCities($id_department)
    {
        $codedep = Location::where('id', $id_department)->first();
        $dep = \DB::table('locations')
            ->select('loc_codedep', 'loc_name', 'id')
            ->where('loc_codecity', '=', $codedep->loc_codedep)
            ->orderBy('loc_name', 'ASC')
            ->get();

        // dd($dep);

        return $dep;
    }

    public static function getDepartments()
    {
        $dep = \DB::table('locations')
            ->select('loc_codedep', 'loc_name', 'id')
            ->where('loc_codecity', '=', 0)
            ->orderBy('loc_name', 'ASC')
            ->get();

        return $dep;
    }

    public function clientsByCity()
    {
        return $this->hasMany(Client::class, 'city_id', 'id');
    }

    public function clientsByDepartment()
    {
        return $this->hasMany(Client::class, 'department_id', 'id');
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class, 'city_id', 'id');
    }

    public function negotiations()
    {
        return $this->hasMany(Negotiation::class, 'city_id', 'id');
    }
}
