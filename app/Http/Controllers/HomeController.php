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

    public function jsonLogin(Request $request){
        $jsonReturn = array(
            '$jason' => array(
                'head' => array(
                    'title' => 'login',
                ) ,
                'body' => array(
                    'style' => array(
                        'border' => 'none',
                        'background' => '#646464',
                    ) ,
                    'header' => array(
                        'title' => 'Sign in',
                        'style' => array(
                            'background' => '#646464',
                            'color' => '#ffffff',
                        ) ,
                    ) ,
                    'sections' => array(
                        0 => array(
                            'items' => array(
                                0 => array(
                                    'type' => 'space',
                                    'height' => '10',
                                ) ,
                                1 => array(
                                    'type' => 'textfield',
                                    'name' => 'email',
                                    'style' => array(
                                        'size' => '20',
                                        'color' => '#8bb92d',
                                        'font' => 'HelveticaNeue-Bold',
                                        'background' => '#575757',
                                        'padding' => '20',
                                    ) ,
                                    'placeholder' => 'enter email',
                                ) ,
                                2 => array(
                                    'type' => 'space',
                                    'height' => '5',
                                ) ,
                                3 => array(
                                    'type' => 'textfield',
                                    'name' => 'password',
                                    'placeholder' => 'enter password',
                                    'style' => array(
                                        'size' => '20',
                                        'color' => '#8bb92d',
                                        'font' => 'HelveticaNeue-Bold',
                                        'background' => '#575757',
                                        'padding' => '20',
                                        'secure' => 'true',
                                    ) ,
                                ) ,
                                4 => array(
                                    'type' => 'space',
                                    'height' => '5',
                                ) ,
                                5 => array(
                                    'type' => 'label',
                                    'style' => array(
                                        'width' => '100%',
                                        'align' => 'right',
                                        'font' => 'HelveticaNeue-Bold',
                                        'size' => '20',
                                        'padding' => '10',
                                        'background' => '#8bb92d',
                                        'color' => '#ffffff',
                                    ) ,
                                    'text' => 'Sign in >',
                                    'action' => array(
                                        'type' => '$network.request',
                                        'options' => array(
                                            'url' => 'http://manage.riggsdesignsolutions.com/json/login',
                                            'method' => 'post',
                                            'data' => array(
                                                'email' => '{{$get.email}}',
                                                'password' => '{{$get.password}}',
                                            ) ,
                                        ) ,
                                        'success' => array(
                                            'type' => '$util.banner',
                                            'options' => array(
                                                'title' => 'Success',
                                                'description' => 'You have sent a request and received a success message: {{$jason.email}  -  {{$jason.authentication_token}',
                                            ) ,
                                            // 'type' => '$session.set',
                                            // 'options' => array(
                                            //     'domain' => 'http://manage.riggsdesignsolutions.com',
                                            //     'header' => array(
                                            //         'X-User-Email' => '{{$jason.email}}',
                                            //         'X-User-Token' => '{{$jason.authentication_token}}',
                                            //     ) ,
                                            // ) ,
                                            'success' => array(
                                                // 'type' => '$href',
                                                // 'options' => array(
                                                //     'url' => 'ROOT/posts.json',
                                                //     'transition' => 'replace',
                                                // ) ,
                                                'type' => '$util.banner',
                                                'options' => array(
                                                    'title' => 'Success',
                                                    'description' => 'You have sent a request and received a success(2)',
                                                ) ,
                                            ) ,
                                        ) ,
                                        'error' => array(
                                            'type' => '$util.banner',
                                            'options' => array(
                                                'title' => 'Error',
                                                'description' => 'Something went wrong. Please check if you entered your email and password correctly',
                                            ) ,
                                        ) ,
                                    ) ,
                                ) ,
                                6 => array(
                                    'type' => 'label',
                                    'style' => array(
                                        'size' => '16',
                                        'font' => 'HelveticaNeue-Bold',
                                        'text' => 'or',
                                        'padding' => '10',
                                        'align' => 'center',
                                    ) ,
                                ) ,
                            ) ,
                        ) ,
                    ) ,
                ) ,
            )
        );

        return response()->json($jsonReturn);
    }

    public function handleJsonLogin(Request $request){
        $input = $request->all();

        $jsonReturn = [
            "id" => 1,
            "email" => $input['email'],
            "authentication_token" => "testAuthenticationToken" . rand()
        ];

        return response()->json($jsonReturn);
    }
}
