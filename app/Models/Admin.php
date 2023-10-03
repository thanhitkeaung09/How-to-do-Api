<?php

namespace App\Models;

//use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasRoles;

    protected $hidden = ['password'];
    protected $guarded = [];
    protected $guard_name = 'web';

    protected function profileImage(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ?  route('api:v1:image:show', $value) : $value,
        );
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'admin_users');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function role()
    {
        return $this->belongsToMany(Role::class);
    }
}
