<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class Helpers extends Controller
{
    public static function parseDateRange($startDate, $endDate){
    	return \Carbon\CarbonPeriod::create($startDate, $endDate)->toArray();
    }

    public static function in_array_all($needles, $haystack) {
	   return empty(array_diff($needles, $haystack));
	}

	public static function userTimeCurrent($format = 'Y-m-d'){
		return Carbon::now()->setTimezone(\Auth::user()->timezone)->format($format);
	}
}
