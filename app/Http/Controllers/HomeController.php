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
            $childAvailPrivileges = Privilege::whereIn('id', $child->privileges)->get();
            $child = Child::setPrivilegeStatus($child, $childAvailPrivileges, $dateRange);
            $child->privilegeStatus = $child->privilegeStatus[$now];
        }

        if($this->jsonRequest){
            $childrenComponents = [
                [
                    'type' => 'label',
                    'text' => "Last updated: " . Helpers::userTimeCurrent('m-d-Y H:i:s'),
                    'style' => [
                        'align' => 'center'
                    ]
                ]
            ];

            $childrenOnly = [];

            foreach($children as $child){
                $childInfo = [];
                $childInfo[] = [
                    'type' => 'label',
                    'text' => $child->name,
                    'style' => [
                        'padding' => 20,
                        'align' => 'center',
                        'size' => '18'
                    ]
                ];

                foreach($child->privilegeStatus as $name => $status){
                    $statusText = $name . ' - ';
                    $statusText .= $status ? 'no' : 'yes';
                    $statusColor = $status ? '#f5c6cb' : '#c3e6cb';

                    $childInfo[] = [
                        'type' => 'label',
                        'text' => $name,
                        'style' => [
                            'background' => $statusColor,
                            'padding' => '10',
                        ]
                    ];
                }

                $childComponent = [
                    'type' => 'vertical',
                    'components' => $childInfo
                ];
                
                $childrenComponents[] = $childComponent;
                $childrenOnly[] = $child->toArray();
            }

            
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
                            '$pull' => [
                                "type" => '$reload'
                            ]
                        ],
                        'templates' => [
                        ],
                        'data' => [
                            'children' => $childrenOnly 
                        ]
                    ],
                    'body' => [
                        'header' => [
                            "title" => "Children Status - " . $now,
                            // 'menu' => [
                            //     'text' => 'menu',
                            //     'style' => [
                            //         'color' => '#0000ff',
                            //         'font' => 'HelveticaNeue-Bold',
                            //         'size' => '17'
                            //     ],
                            //     'action' => [
                            //         'type' => '$util.toast',
                            //         'options' => [
                            //             'text' => 'Good Job!'
                            //         ]
                            //     ]
                            // ]
                        ],
                        'sections' => [
                            [
                                'items' => $childrenComponents
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
        
        $childAvailPrivileges = Privilege::whereIn('id', $child->privileges)->get();

        $child = Child::setPrivilegeStatus($child, $childAvailPrivileges, $dateRange);

        $privilegeEvents = Child::privilegeEvents($child->privilegeStatus);
        
        return response()->json($privilegeEvents);
    }
}
