<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\RecipeStep;
use App\Category;
use App\RecipeCategory;
use Auth;
use DB;

class RecipeStepController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'recipe_id' => 'required', 
                'title' => 'required', 
                'description' => 'required', 
                'image' => 'required',
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

            // Store Recipe Step
            RecipeStep::create([
                'recipe_id' => $request->recipe_id,
                'title' => $request->title,
                'description' =>$request->description,
                'image_name' =>$file_name,
            ]);

            DB::commit();

            return response()->json(['message' => 'Recipe step create successfully done.'], 200);

        }catch(Exception $e) {
            DB::rollback();
            return back()->with('error','There is something error, please try after some time');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            
            $validatedData = $request->validate([
                'title' => 'required', 
                'description' => 'required', 
                'image' => 'required',
                'categories' => 'required',
            ]);

            $recipeStep = RecipeStep::where('id', $id)->first();

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

            if(isset($file_name) && $recipeStep->image_name == $file_name){
                $fileName = $recipeStep->image_name;
            }else if(isset($file_name) && $recipeStep->image_name != $file_name){
                $destinationPath = 'images/';
                File::delete($destinationPath.$recipeStep->image_name);
                $fileName = $file_name;
            }else if(!isset($file_name) && $recipeStep->image_name){
                $fileName = $recipeStep->image_name;
            }else{
                $fileName = null;
            }

            // Update Recipe Step
            RecipeStep::where('id', $recipeStep->id)->update([
                'title' => $request->title,
                'description' =>$request->description,
                'image_name' =>$fileName,
            ]);

            DB::commit();

            return response()->json(['message' => 'Recipe step update successfully done.'], 200);

        }catch(Exception $e) {
            DB::rollback();
            return back()->with('error','There is something error, please try after some time');
        }  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
