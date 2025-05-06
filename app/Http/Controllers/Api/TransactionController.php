<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        return Transaction::with(['produit', 'acheteur'])->get();
    }

    public function parAcheteur($id)
{
    return \App\Models\Transaction::with(['produit'])
        ->where('acheteur_id', $id)
        ->get();
}

public function parVendeur($id)
{
    return \App\Models\Transaction::with(['produit'])
        ->whereHas('produit', function ($query) use ($id) {
            $query->where('user_id', $id); // le vendeur est l'auteur du produit
        })
        ->get();
}

}

