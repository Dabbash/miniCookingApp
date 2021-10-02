<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = [
        'user_id', 'title', 'description', 'image_name',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function ingredients(){
        return $this->hasMany(Ingredient::class, 'recipe_id', 'id');
    }

    public function category_relation(){
        return $this->hasMany(RecipeCategory::class, 'recipe_id', 'id');
    }

    public function steps(){
        return $this->hasMany(RecipeStep::class, 'recipe_id', 'id');
    }
}
