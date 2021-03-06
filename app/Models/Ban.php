<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ban extends Model
{
    protected $table = 'bans';

	public $timestamps = true;

	protected $fillable = [
        'user_id', 'reason',
    ];

	public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
