<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

/**
 * Class User
 *
 * @mixin Builder
 * @package App\Models
 */
class User extends Model implements \Illuminate\Contracts\Auth\Authenticatable
{
    use Authenticatable, HasApiTokens;

    const ROLES = [
        'admin' => 'admin',
        'user'  => 'user',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'role_id',
        'password',
        'positive_color',
        'negative_color',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * @param $password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = $password ? Hash::make($password) : $password;
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class)->withDefault([]);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class)->withTimestamps();
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function isAdmin():bool{
        return $this->role->name == static::ROLES['admin'];
    }

    public function hasPermission($name):bool{
        return $this->permissions()->where('name',$name)->exists();
    }
    public function canOpenOrder():bool{
        return $this->hasPermission('open');
    }
    public function canCloseOrder():bool{
        return $this->hasPermission('close');
    }
    public function canRankOrder():bool{
        return $this->hasPermission('rank');
    }
}
