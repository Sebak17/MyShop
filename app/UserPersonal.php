<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPersonal extends Model
{
    
	protected $table = 'users_personal';

	protected $fillable = [
        'user_id', 'firstname', 'surname', 'phoneNumber',
    ];

	public $timestamps = false;

	public function user()
    {
        return $this->belongsTo('App\User');
    }

}
