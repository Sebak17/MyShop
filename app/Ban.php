<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ban extends Model
{
    protected $table = 'bans';

	public $timestamps = false;

	protected $fillable = [
        'user_id', 'reason',
    ];

	public function user()
    {
        return $this->belongsTo('App\User');
    }
}
