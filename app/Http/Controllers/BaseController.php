<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected $jsonRequest = null;

    public function __construct(Request $request)
    {
        if(!is_null($request->route()->getPrefix()) && $request->route()->getPrefix() == "/json"){
        	$this->jsonRequest = true;
        }
    }
}
