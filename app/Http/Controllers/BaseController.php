<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected $jsonRequest = null;

    public function __construct(Request $request)
    {
        if(!\Auth::check()){
        	\Auth::attempt(['email' => 'adam.riggsy@gmail.com', 'password' => 'Sophie2006*']);
        }

        if(!is_null($request->route()->getPrefix()) && $request->route()->getPrefix() == "/json"){
        	$this->jsonRequest = true;
        }
    }
}
