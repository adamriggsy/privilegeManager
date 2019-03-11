<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Child;
use App\User;
use App\Privilege;
use Carbon\Carbon;

class ChildrenController extends Controller
{
    public $child = null;
    public $childAvailPrivileges = null;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        if(! \App::runningInConsole()){
            if(!is_null(\Route::current()->parameter('id'))){
                $id = (int) \Route::current()->parameter('id');
            }else if(!is_null(\Route::current()->parameter('child'))){
                $id = (int) \Route::current()->parameter('child');
            }else{
                $id = 10;
            }
        }else{
            $id = 1;
        }

        //dd($id, \Route::current()->parameter('id'), \Route::current()->parameter('child'));

        $this->child = Child::find($id);
        //get the available privileges for the child
        $this->childAvailPrivileges = Privilege::whereIn('id', $this->child->privileges)->get();
        $this->userAvailPrivileges = Privilege::whereIn('id', $this->child->user->my_privileges)->get();
        //dd($this->childAvailPrivileges, $this->child->user->my_privileges, $this->child->privileges);
        //dd($this->childAvailPrivileges);
    }

    /**
     * Show the user's children.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $children = User::find(\Auth::id())->children;
        
        return view('children')->withChildren($children);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('child-create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Child $child)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Child $child)
    {
        return view('child-edit')->with('child', $child);
        dd($child);
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

    public function managePrivileges($id, $startDay = false, $endDay = false){
    	if($startDay === false){
            $startDay = Helpers::userTimeCurrent();
        }

        if($endDay === false){
            $endDay = Helpers::userTimeCurrent();
        }

        $child = Child::find($id);
    	//get the available privileges for the child
        $allPrivileges = $this->childAvailPrivileges;

        $dateRange = Helpers::parseDateRange($startDay, $endDay);

        $child = Child::setPrivilegeStatus($child, $allPrivileges, $dateRange);
        
        return view('child-manage')->withChild($child)->with('allPrivileges', $allPrivileges);
    }

    public function banPrivilege($childID, Request $request){
        if(self::isUserChild($childID) !== false){
            return view('child-privilege-management')
                ->with('child', $this->child)
                ->with('parameters', $request->all())
                ->with('allPrivileges', $this->childAvailPrivileges)
                ->with('action', 'ban');
            dd($child, $request->all());
        }
    }

    public function banPrivilegeProcess($childID, Request $request){
        if(self::isUserChild($childID) !== false){
            
            
            $dateRange = Helpers::parseDateRange($request->get('datepicker_start'), $request->get('datepicker_end'));
            
            $numAdded = 0;
            foreach ($dateRange as $date) {
                $hasPrivilege = self::checkPrivilegeBan($this->child, $request->get('privilege'), $date->format('Y-m-d'));
                
                if(!$hasPrivilege){
                    $privilegeInfo = [
                        $request->get('privilege') => [
                            'start_ban' => $date->format('Y-m-d')
                        ]
                    ];

                    $child = $this->child->privileges()->attach($privilegeInfo);    
                    $numAdded++;
                }
            }
            
            if($numAdded > 0){
                $request->session()->flash('status', 'You have successfully added new privilege bans');
            }else{
                $request->session()->flash('error', 'No new privilege bans were added.');
            }
            return redirect()->route('child.manage', ['id' => $this->child->id]);
        }
    }

    public function restorePrivilege($childID, Request $request){
        if(self::isUserChild($childID) !== false){
            return view('child-privilege-management')
                ->with('child', $this->child)
                ->with('parameters', $request->all())
                ->with('allPrivileges', $this->childAvailPrivileges)
                ->with('action', 'restore');
        }
    }

    public function restorePrivilegeProcess($childID, Request $request){
        if(self::isUserChild($childID) !== false){
            $dateRange = Helpers::parseDateRange($request->get('datepicker_start'), $request->get('datepicker_end'));
            
            $numProcessed = 0;
            foreach ($dateRange as $date) {
                $theID = $this->child->privileges()
                    ->where('privilege_id', $request->get('privilege'))
                    ->where('start_ban', '=', $date->format('Y-m-d'))
                    ->withPivot('id')
                    ->first();


                if(isset($theID->pivot->id)){
                    $this->child->privileges()->wherePivot('id', $theID->pivot->id)->detach();
                    $numProcessed++;
                }
            }
            
            if($numProcessed > 0){
                $request->session()->flash('status', 'You have successfully restored the privileges');
            }else{
                $request->session()->flash('error', 'Not able to restore privileges');
            }
            return redirect()->route('child.manage', ['id' => $this->child->id]);
        }
    }

    public function childPrivilegesUpdate(\Request $request){
        $privileges = $request::input('privileges');

        $this->child->privileges = $privileges;
        $wasSaved = $this->child->save();

        return response()->json($wasSaved);
    }

    public static function isUserChild($childID){
        return array_search($childID, array_column(User::find(\Auth::id())->children->toArray(), 'id'));
    }

    public static function checkPrivilegeBan(Child $child, $privilegeID, $date){
        return $child->privileges()
                    ->where('privilege_id', $privilegeID)
                    ->where('start_ban', '=', $date)
                    ->exists();
    }
}
