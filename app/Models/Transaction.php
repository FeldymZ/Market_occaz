<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['produit_id', 'acheteur_id', 'prix', 'date_transaction'];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function acheteur()
    {
        return $this->belongsTo(User::class, 'acheteur_id');
    }
}
