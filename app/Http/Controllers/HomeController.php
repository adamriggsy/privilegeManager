<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Child;
use App\User;
use App\Privilege;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
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

        $privilegeStatus = [];

        foreach ($children as $key => $child) {
            $child = Child::setPrivilegeStatus($child, $allPrivileges);;
        }

        return view('children-status')->withChildren($children)->withAvailablePrivileges($allPrivileges);
    }
}
