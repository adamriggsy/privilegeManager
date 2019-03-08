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
        $this->middleware('auth');
        $id = \Route::current()->parameter('id');
        $this->child = Child::find($id);
        //get the available privileges for the child
        $this->childAvailPrivileges = Privilege::whereIn('id', $this->child->user->privileges)->get();

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
            $startDay = Carbon::now();
        }

        if($endDay === false){
            $endDay = Carbon::now();
        }

        $child = Child::find($id);
    	//get the available privileges for the child
        $allPrivileges = $this->childAvailPrivileges;

        $dateRange = Helpers::parseDateRange($startDay, $endDay);

        $child = Child::setPrivilegeStatus($child, $allPrivileges, $dateRange);
        
        return view('child-manage')->withChild($child)->with('allPrivileges', $allPrivileges);
    }

    public function childPrivilegesAPI($childID, Request $request){
        if(self::isUserChild($childID) !== false){
            $requestVars = $request->all();
            $dateRange = Helpers::parseDateRange($requestVars['start'], $requestVars['end']);
            
            $allPrivileges = $this->childAvailPrivileges;

            $child = Child::setPrivilegeStatus($this->child, $allPrivileges, $dateRange);

            $privilegeEvents = Child::privilegeEvents($child->privilegeStatus);
            
            return response()->json($privilegeEvents);
        }else{
            return response()->json([]);
        }
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
                    ->first();

                if(isset($theID->id)){
                    $this->child->privileges()->detach($theID->id);
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
