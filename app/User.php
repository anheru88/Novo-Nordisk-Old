<?php

namespace App;

use Caffeinated\Shinobi\Concerns\HasRolesAndPermissions;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    use HasRolesAndPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [ ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Los atributos que son asignados en masa
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    public static function getUsers($idUser)
    {
        $user = \DB::table('users')
        ->select('nickname','name','email','phone')
        ->where('id', '=', $idUser)
        ->orderBy('name', 'ASC')
        ->get();
        //dd($user);
        return $user;
    }

    public function discLevel(){
        return $this->belongsTo(DiscountLevels::class,'authlevel');
        //return $this->hasMany(DiscountLevels::class,'authlevel');
    }

    public function qapprovers(){
        return $this->belongsTo(nvn_QuotationApprovers::class,'ApproversUser');
        //return $this->hasMany(QuotationApprovers::class);
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    public function getUrlPathAtribute()
    {
        return Storage::url($this->attributes['firm']);
    }

}
