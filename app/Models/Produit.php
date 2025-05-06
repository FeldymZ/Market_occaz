<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $fillable = [
        'titre',
        'description',
        'prix',
        'categorie',
        'statut',
        'user_id',
    ];

    public function user()
{
    return $this->belongsTo(User::class);
}

public function transaction()
{
    return $this->hasOne(Transaction::class);
}

    
}



