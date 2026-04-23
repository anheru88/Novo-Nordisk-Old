<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

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

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user')->withTimestamps();
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_user')->withTimestamps();
    }

    public function hasRole(string|array $slug): bool
    {
        return $this->roles()->whereIn('slug', (array) $slug)->exists();
    }

    public function hasPermissionTo(string $slug): bool
    {
        if ($this->roles()->where('special', 'all-access')->exists()) {
            return true;
        }

        if ($this->permissions()->where('slug', $slug)->exists()) {
            return true;
        }

        return $this->roles()
            ->whereHas('permissions', fn ($q) => $q->where('slug', $slug))
            ->exists();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return ! $this->roles()->where('special', 'no-access')->exists();
    }
}
