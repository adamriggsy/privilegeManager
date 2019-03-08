<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Privilege extends Model
{
    public function children()
    {
    	return $this->belongsToMany('App\Child')->withPivot('start_ban')->withTimestamps();
    }
}
