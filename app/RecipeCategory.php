<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecipeCategory extends Model
{
    protected $fillable = [
        'recipe_id', 'category_id'
    ];

    public function recipies(){
        return $this->hasOne(Recipe::class, 'id', 'recipe_id');
    }

    public function categories(){
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
}
