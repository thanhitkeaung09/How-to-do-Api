<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;
    //    protected $dates = ['deleted_at'];
    protected $guarded = [];
    //to show image coverImage should be the same with the key in contorller
    protected function coverImage(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ?  route('api:v1:image:show', $value) : $value,
        );
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'post_reads');
    }

    public function usersShare()
    {
        return $this->belongsToMany(User::class, 'post_shares');
    }

    public function usersSave()
    {
        return $this->belongsToMany(User::class, 'post_saves')->withTimestamps();
    }

    public function postRead()
    {
        return $this->belongsToMany(User::class, 'post_reads');
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function likeArticle(): MorphMany
    {
        return $this->morphMany(User::class, 'likeable');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function cat()
    {
        return $this->belongsToMany(User::class, 'category_user');
    }
}
