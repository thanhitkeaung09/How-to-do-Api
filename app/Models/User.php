<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'social_id',
        'social_type',
        'device_token',
        'profile_image',
        'language'
    ];

    //    protected $hidden = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected function profileImage(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? route('api:v1:image:show', $value) : $value,
        );
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function admins(): BelongsToMany
    {
        return $this->belongsToMany(Admin::class, 'admin_users');
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_saves')->withTimestamps();
    }

    public function postReads(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_reads');
    }

    public function following_lists()
    {
        return $this->belongsToMany(Admin::class, 'admin_users');
    }

    public function popularCategories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_users');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class, 'user_id', 'id');
    }

    public function trendposts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'category_user');
    }
}
