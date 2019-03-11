<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Child;
use App\User;
use App\Privilege;
use App\Http\Controllers\ChidrenController;
use App\Http\Controllers\BaseController;

class HomeController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        //$this->middleware('auth');
        parent::__construct($request);
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
        //$children = User::find(\Auth::id())->children;
        $children = User::find(1)->children;
        $allPrivileges = Privilege::all('name')->toArray();
        $now = Helpers::userTimeCurrent();

        $dateRange = Helpers::parseDateRange($now, $now);

        $privilegeStatus = [];

        foreach ($children as $key => $child) {
            $child = Child::setPrivilegeStatus($child, $allPrivileges, $dateRange);;
            $child->privilegeStatus = $child->privilegeStatus[$now];
        }

        if($this->jsonRequest){
            $jsonReturn = [
                '$jason' => [
                    'head' => [
                        'title' => 'Children Status',
                        'description' => 'Quickly see the status of your children\'s privileges' ,
                        'icon' => '',
                        'offline' => 'true',
                        'styles' => [
                        ],
                        'actions' => [
                        ],
                        'templates' => [
                        ],
                        'data' => [
                        ]
                    ],
                    'body' => [
                        'header' => [
                            "title" => "Privilege Manager",
                            'menu' => [
                                'text' => 'menu',
                                'style' => [
                                    'color' => '#0000ff',
                                    'font' => 'HelveticaNeue-Bold',
                                    'size' => '17'
                                ],
                                'action' => [
                                    'type' => '$util.toast',
                                    'options' => [
                                        'text' => 'Good Job!'
                                    ]
                                ]
                            ]
                        ],
                        'sections' => [
                            [
                                'type' => 'label',
                                'text' => 'Testing yes'
                            ]
                        ]
                    ]
                ]
            ];
            return response()->json($jsonReturn);
            dd($this->jsonRequest, json_encode($jsonReturn));
        }else{
            return view('children-status')->withChildren($children)->withAvailablePrivileges($allPrivileges);
        }
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
