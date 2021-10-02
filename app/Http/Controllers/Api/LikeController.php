<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Recipe;
use App\Likes;
use Auth;

class LikeController extends Controller
{
    public function doLike(Request $request){

        // return response()->json($request->all(), 200);
        
        Likes::create([
            'user_id' => Auth::user()->id,
            'recipe_id' => $request->recipe_id,
        ]);

        return response()->json(['message' => 'Like done.'], 200);
    }

    public function getLikedRecipes(Request $request){
        $likes = Likes::where('user_id', Auth::user()->id)
        ->with('recipe')
        ->get();

        return response()->json($likes, 200);
    }
}
