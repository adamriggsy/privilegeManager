<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected $jsonRequest = null;

    public function __construct(Request $request)
    {
        if(! \App::runningInConsole()){
            //dd(strpos($request->route()->getPrefix(), "json") !== false);
            if(!is_null($request->route()->getPrefix()) && strpos($request->route()->getPrefix(), "json") !== false){
            	$this->jsonRequest = true;
            }
        }else{
            $this->jsonRequest = false;
        }
    }
}
