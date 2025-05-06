<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'note' => 'required|integer|min:1|max:5',
            'commentaire' => 'nullable|string',
            'vendeur_id' => 'required|exists:users,id',
            'acheteur_id' => 'required|exists:users,id',
        ]);
    
        $notation = \App\Models\Notation::create($request->all());
    
        return response()->json([
            'message' => 'Notation enregistrée avec succès',
            'notation' => $notation
        ], 201);
    }
    
}
