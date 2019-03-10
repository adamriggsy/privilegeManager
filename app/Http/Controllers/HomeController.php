<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Child;
use App\User;
use App\Privilege;
use App\Http\Controllers\ChidrenController;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Show the user's children's privilege status.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function childrenStatus()
    {
        $children = User::find(\Auth::id())->children;
        $allPrivileges = Privilege::all('name')->toArray();
        $now = Helpers::userTimeCurrent();

        $dateRange = Helpers::parseDateRange($now, $now);

        $privilegeStatus = [];

        foreach ($children as $key => $child) {
            $child = Child::setPrivilegeStatus($child, $allPrivileges, $dateRange);;
            $child->privilegeStatus = $child->privilegeStatus[$now];
        }

        return view('children-status')->withChildren($children)->withAvailablePrivileges($allPrivileges);
    }

    public function childPrivilegesAPI($childID, Request $request){
        $user = \Auth::guard('api')->user();

        $child = Child::find($childID);
        $requestVars = $request->all();
        $dateRange = Helpers::parseDateRange($requestVars['start'], $requestVars['end']);
        
        $allPrivileges = Privilege::whereIn('id', $child->privileges)->get();

        $child = Child::setPrivilegeStatus($child, $allPrivileges, $dateRange);

        $privilegeEvents = Child::privilegeEvents($child->privilegeStatus);
        
        return response()->json($privilegeEvents);
    }
}
