<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    
	protected $table = 'users_info';

	public $timestamps = false;

	protected $fillable = [
        'user_id', 'firstIP', 'activationHash', 'activationMailTime', 'passwordResetHash', 'passwordResetMailTime',
    ];

	public function user()
    {
        return $this->belongsTo('App\User');
    }

}
