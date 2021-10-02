<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::with('recipe_relation.recipies')->get();

        return response()->json($categories, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category) {
        return new CategoryResource($category);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'title_ar' => 'required',
        ]);
        return auth()->user()->categories()->create($request->all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        if (auth()->id() != $category->user_id) {
            return response()->json(['message' => 'You dont own this resource'], 401);
        }

        $validateData = $request->validate([
            'title_ar' => 'required',
        ]);

        if($category->update($request->all())) {
            return response()->json(['message' => 'updated']);
        } else {
            return response()->json(['message' => 'error'], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if (auth()->id() != $category->user_id) {
            return response()->json(['message' => 'You dont own this resource'], 401);
        }

        if($category->delete()) {
            return response()->json(['message' => 'deleted']);
        }

        return response()->json(['message' => 'error, try again'], 500);
    }
}
