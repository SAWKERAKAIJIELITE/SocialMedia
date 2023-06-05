<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'user_identifier',
        'birth_date',
        'email',
        'password',
        'phone_number',
        'current_location',
        'programming_age',
        'gender',
        'bio',
        'image_path',
        'country'
    ];

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
        'password' => 'hashed',
    ];

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function expert(): HasOne
    {
        return $this->hasOne(Expert::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'admin_id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(Reaction::class);
    }

    public function favoritePosts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class,'favorite_posts')
            ->using(FavoritePost::class)
            ->as('favorite');
    }

    public function communities(): BelongsToMany
    {
        return $this->belongsToMany(Community::class)
            ->withTimestamps()
            ->as('subscription');
    }

    public function memberPages(): BelongsToMany
    {
        return $this->belongsToMany(Page::class);
    }

    public function specialties(): BelongsToMany
    {
        return $this->belongsToMany(Specialty::class,'knowledge')
            ->using(Knowledge::class)
            ->as('know');
    }
}
