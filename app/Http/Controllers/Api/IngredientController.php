<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Ingredient;
use App\Recipe;
use Illuminate\Http\Request;
use App\Category;
use App\RecipeCategory;
use Auth;
use DB;

class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(isset($request->recipe_id)){

            $recipies = RecipeStep::where(['recipe_id' => $request->recipe_id])
            ->get();

            return response()->json($recipies, 422);
        }else{
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'recipe_id' => 'required', 
            'name' => 'required', 
            'qty' => 'required', 
        ]);

        $recipe = Recipe::findOrFail($validatedData['recipe_id']);

        if(auth()->id() != $recipe->user_id){
            return response()->json(['message' => 'you dont own thid recipe'], 401);
        }

        $request['user_id'] = auth()->id();

        $ingredient = $recipe->ingredients()->create($request->all());

        if($ingredient) { 
            return $ingredient;
        }
        
        return response()->json(['message' => 'error'], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ingredient  $ingredient
     * @return \Illuminate\Http\Response
     */
    public function show(Ingredient $ingredient)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ingredient  $ingredient
     * @return \Illuminate\Http\Response
     */
    public function edit(Ingredient $ingredient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ingredient  $ingredient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ingredient $ingredient)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ingredient  $ingredient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ingredient $ingredient)
    {
        //
    }
}
