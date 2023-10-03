<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    protected $guarded  = [];

    protected function icon(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => route('api:v1:image:show', $value),
        );
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function usersCategory(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'category_users');
    }

    public function trendposts(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'category_user');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function admin()
    {
        return $this->hasMany(Admin::class);
    }
}
