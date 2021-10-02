<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = [
        'user_id', 'recipe_id', 'name', 'qty'
    ];

    public function recipe(){
        return $this->belongsTo(Recipe::class);
    }
}
