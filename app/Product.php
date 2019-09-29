<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
   
	protected $fillable = [
        'price', 'title', 'description', 'images', 'category_id',
    ];

}
