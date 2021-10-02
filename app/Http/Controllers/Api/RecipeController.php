<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Recipe;
use App\Category;
use App\RecipeCategory;
use Auth;
use Illuminate\Http\Request;
use DB;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(isset($request->category_id)){

            $recipies = Recipe::select('recipes.*')
            ->with('category_relation.categories', 'steps')
            ->with('category_relation.categories', 'ingredients')
            ->join('recipe_categories', 'recipe_categories.recipe_id', 'recipes.id')
            ->where(['recipe_categories.category_id' => $request->category_id])
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

        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'title' => 'required', 
                'description' => 'required', 
                'image' => 'required',
                'categories' => 'required',
            ]);

            // Check Image Validation 
            if(request()->file('image')){
                $validator = Validator::make($request->all(), [
                    'image' => 'required|mimes:jpg,png,jpeg'
                ]);
                if ($validator->fails()) {
                    return back()->with('error','Supported files is  JPG or PNG or JPEG');
                }

                $file = request()->file('image');
                $file_name = $file->getClientOriginalName();
                $file->move('images', $file_name);
            }

            // Store Recipe
            $recipe = Recipe::create([
                'user_id' => Auth::user()->id,
                'title' => $request->title,
                'description' =>$request->description,
                'image_name' =>$file_name,
            ]);

            // Store Recipe Category 
            foreach ($request->categories as $category) {
                RecipeCategory::create([
                    'recipe_id' => $recipe->id,
                    'category_id' =>$category,
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Recipe create successfully done.'], 200);

        }catch(Exception $e) {
            DB::rollback();
            return back()->with('error','There is something error, please try after some time');
        }  
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function show(Recipe $recipe)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function edit(Recipe $recipe)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recipe $recipe)
    {

        try {
            DB::beginTransaction();
            
            $validatedData = $request->validate([
                'title' => 'required', 
                'description' => 'required', 
                'image' => 'required',
                'categories' => 'required',
            ]);

             // Check Image Validation 
             if(request()->file('image')){
                $validator = Validator::make($request->all(), [
                    'image' => 'required|mimes:jpg,png,jpeg'
                ]);
                if ($validator->fails()) {
                    return back()->with('error','Supported files is  JPG or PNG or JPEG');
                }

                $file = request()->file('image');
                $file_name = $file->getClientOriginalName();
                $file->move('images', $file_name);
            }

            if(isset($file_name) && $recipe->image_name == $file_name){
                $fileName = $recipe->image_name;
            }else if(isset($file_name) && $recipe->image_name != $file_name){
                $destinationPath = 'images/';
                File::delete($destinationPath.$recipe->image_name);
                $fileName = $file_name;
            }else if(!isset($file_name) && $recipe->image_name){
                $fileName = $recipe->image_name;
            }else{
                $fileName = null;
            }

            // Update Recipe
            Recipe::where('id', $recipe->id)->update([
                'user_id' => Auth::user()->id,
                'title' => $request->title,
                'description' =>$request->description,
                'image_name' =>$fileName,
            ]);

            // delete old category relation
            RecipeCategory::where('recipe_id', $recipe->id)->delete();

            // Store Fresh Recipe Category 
            foreach ($request->categories as $category) {
                RecipeCategory::create([
                    'recipe_id' => $recipe->id,
                    'category_id' =>$category,
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Recipe update successfully done.'], 200);

        }catch(Exception $e) {
            DB::rollback();
            return back()->with('error','There is something error, please try after some time');
        }  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recipe $recipe)
    {
       // delete recipe
       Recipe::where('recipe_id', $recipe->id)->delete();

       // delete all category relation
       RecipeCategory::where('recipe_id', $recipe->id)->delete();

       return response()->json(['message' => 'Recipe delete successfully done.'], 200);
    }
}
