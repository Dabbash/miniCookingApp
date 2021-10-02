<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Recipe;
use App\Favorite;
use Auth;

class FavoriteController extends Controller
{
    public function doFavorite(Request $request){
        Favorite::create([
            'user_id' => Auth::user()->id,
            'recipe_id' => $request->recipe_id,
        ]);

        return response()->json(['message' => 'Favorite done.'], 200);
    }

    public function getFavoriteRecipes(Request $request){
        $favorites = Favorite::where('user_id', Auth::user()->id)
        ->with('recipe')
        ->get();

        return response()->json($favorites, 200);
    }
}
