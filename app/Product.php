<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

	protected $table = 'products';

	public $timestamps = false;
   
	protected $fillable = [
        'price', 'title', 'description', 'status', 'category_id', 'params',
    ];

    public function images()
    {
        return $this->hasMany('App\ProductImage');
    }

}
