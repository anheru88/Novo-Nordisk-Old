<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, HasRoles, Notifiable;

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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
        return $this->belongsTo(DiscountLevel::class,'authlevel');
        //return $this->hasMany(DiscountLevel::class,'authlevel');
    }

    public function qapprovers(){
        return $this->belongsTo(QuotationApprover::class,'ApproversUser');
        //return $this->hasMany(QuotationApprover::class);
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    public function getUrlPathAtribute()
    {
        return Storage::url($this->attributes['firm']);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return ! $this->hasRole('inactivo');
    }
}
