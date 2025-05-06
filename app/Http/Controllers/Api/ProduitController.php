<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;

class ProduitController extends Controller
{
    
    public function index()
    {
        return Produit::with(['user' => function ($query) {
            $query->select('id', 'name', 'email');
        }])->get()->map(function ($produit) {
            $produit->user->note_moyenne = $produit->user->note_moyenne;
            $produit->user->nombre_avis = $produit->user->nombre_avis;
            return $produit;
        });
    }
    
    


    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string',
            'description' => 'nullable|string',
            'prix' => 'required|numeric',
            'categorie' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);

        return Produit::create($request->all());
    }

    public function show($id)
    {
        return Produit::with('user')->findOrFail($id);
    }
    

    public function update(Request $request, $id)
    {
        $produit = Produit::findOrFail($id);
        $produit->update($request->all());

        return $produit;
    }

    public function destroy($id)
    {
        Produit::destroy($id);
        return response()->noContent();
    }
    public function search(Request $request)
    {
        $query = Produit::with('user');
    
        if ($request->filled('titre')) {
            $query->where('titre', 'ILIKE', '%' . $request->titre . '%');
            // Utilise 'ILIKE' pour PostgreSQL (insensible à la casse)
        }
    
        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }
    
        if ($request->filled('prix_min')) {
            $query->where('prix', '>=', $request->prix_min);
        }
    
        if ($request->filled('prix_max')) {
            $query->where('prix', '<=', $request->prix_max);
        }
    
        return $query->get();
    }


    public function acheter(Request $request, $id)
    {
        $produit = Produit::findOrFail($id);
    
        if ($produit->statut === 'vendu') {
            return response()->json(['message' => 'Produit déjà vendu.'], 400);
        }
    
        $request->validate([
            'acheteur_id' => 'required|exists:users,id',
        ]);
    
        // Enregistrer la transaction
        Transaction::create([
            'produit_id' => $produit->id,
            'acheteur_id' => $request->acheteur_id,
            'prix' => $produit->prix,
        ]);
    
        // Mettre à jour le statut du produit
        $produit->statut = 'vendu';
        $produit->save();
    
        return response()->json([
            'message' => 'Produit acheté et transaction enregistrée.',
            'produit' => $produit
        ]);
    }

    
}

