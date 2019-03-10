<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Privilege;

class PrivilegeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Is automatically redirected to the user home page
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = User::find(\Auth::id());

        return view('privilege-create')->with('user', $user);
        dd($user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $privilege = new Privilege;

        $privilege->name = $request->get('privilegeName');

        if($privilege->save()){
            $user = User::find(\Auth::id());
            $myPrivileges = $user->my_privileges;
            array_push($myPrivileges, $privilege->id);

            $user->my_privileges = $myPrivileges;
            $user->save();
            
            $request->session()->flash('status', 'You have successfully added a new privilege');
        }else{
            $request->session()->flash('error', 'Could not create the privilege');
        }

        
        return redirect()->route('privileges.create');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
