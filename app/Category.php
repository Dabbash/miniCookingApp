<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'user_id', 'title_ar', 'title_en',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function recipe_relation(){
        return $this->hasMany(RecipeCategory::class, 'category_id', 'id');
    }
}
