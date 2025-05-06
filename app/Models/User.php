<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];

        
    }

    public function produits()
{
    return $this->hasMany(Produit::class);
}


public function notationsRecues()
{
    return $this->hasMany(Notation::class, 'vendeur_id');
}

public function notationsDonnees()
{
    return $this->hasMany(Notation::class, 'acheteur_id');
}
public function getNoteMoyenneAttribute()
{
    return round($this->notationsRecues()->avg('note'), 1);
}

public function getNombreAvisAttribute()
{
    return $this->notationsRecues()->count();
}

public function achats()
{
    return $this->hasMany(Transaction::class, 'acheteur_id');
}


}
