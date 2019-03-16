<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Child;
use App\User;
use App\Privilege;
use App\Http\Controllers\ChidrenController;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\AndroidApp;
use Carbon\Carbon;

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
        if($this->jsonRequest){
            $user = \Auth::guard('api')->user();
        }else{
            $user = User::find(\Auth::id());
        }

        $children = $user->children;
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
                //dd($child->privilegeStatus);
                foreach($child->privilegeStatus as $name => $status){
                    $statusText = $name . ' - ';
                    $statusText .= $status ? 'no' : 'yes';
                    $statusColor = $status ? '#f5c6cb' : '#c3e6cb';
                    $modalLink = $status ? 'http://manage.riggsdesignsolutions.com/api/json/child/' . $child->id . '/privilege/restore' : 'http://manage.riggsdesignsolutions.com/api/json/child/' . $child->id . '/privilege/ban';

                    $modalLink .= "?date=" . $now;
                    $modalLink .= "&privilege=" . $name;

                    $childInfo[] = [
                        'type' => 'label',
                        'text' => $name,
                        'style' => [
                            'background' => $statusColor,
                            'padding' => '10',
                        ],
                        'action' => [
                            'type' => '$href',
                            'options' => [
                                'url' => $modalLink,
                                'transition' => 'modal',
                            ],
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

            $options = [
                "title" => "Children Status",
                "description" => "Quickly see the status of your children's privileges",
                "bodyTitle" => "Children Status - " . $now,
                "includeFooter" => true,
                "sectionItems" => $childrenComponents
            ];

            $jsonReturn = AndroidApp::createJasonetteWrapper($options);
            $jsonReturn['$jason']['head']['data']['children'] = $childrenOnly;
            $jsonReturn['$jason']['head']['actions'] = [
                '$pull' => [
                    "type" => '$reload'
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
        $jsonReturn = [];

        if(!is_null($request->header('Logged-In')) && $request->header('Logged-In') === "true"){
            if(!is_null($request->header('Logged-Date'))){
                $date = Carbon::parse($request->header('Logged-Date'));
                $now = Carbon::now();
                $diff = $date->diffInDays($now);
                
                if($diff < 10){
                    return redirect()->route('getChildrenStatusJSON');
                }
            }
        }

        $options = [
            "title" => "login",
            "description" => "",
            "bodyTitle" => "Sign in",
            "includeFooter" => false,
            "sectionItems" => [
                0 => [
                    'type' => 'space',
                    'height' => '10',
                ],
                1 => [
                    'type' => 'textfield',
                    'name' => 'email',
                    'style' => [
                        'size' => '20',
                        'color' => '#8bb92d',
                        'font' => 'HelveticaNeue-Bold',
                        'background' => '#575757',
                        'padding' => '20',
                    ],
                    'placeholder' => 'enter email',
                ],
                2 => [
                    'type' => 'space',
                    'height' => '5',
                ],
                3 => [
                    'type' => 'textfield',
                    'name' => 'password',
                    'placeholder' => 'enter password',
                    'style' => [
                        'size' => '20',
                        'color' => '#8bb92d',
                        'font' => 'HelveticaNeue-Bold',
                        'background' => '#575757',
                        'padding' => '20',
                        'secure' => 'true',
                    ],
                ],
                4 => [
                    'type' => 'space',
                    'height' => '5',
                ],
                5 => [
                    'type' => 'label',
                    'style' => [
                        'width' => '100%',
                        'align' => 'right',
                        'font' => 'HelveticaNeue-Bold',
                        'size' => '20',
                        'padding' => '10',
                        'background' => '#8bb92d',
                        'color' => '#ffffff',
                    ],
                    'text' => 'Sign in >',
                    'action' => [
                        'type' => '$network.request',
                        'options' => [
                            'url' => 'http://manage.riggsdesignsolutions.com/json/login',
                            'method' => 'post',
                            'data' => [
                                'email' => '{{$get.email}}',
                                'password' => '{{$get.password}}',
                            ],
                        ],
                        'success' => [
                            // 'type' => '$util.banner',
                            // 'options' => array(
                            //     'title' => 'Success - {{$jason.email}}',
                            //     'description' => '{{$jason.authentication_token}}',
                            // ) ,
                            'type' => '$session.set',
                            'options' => [
                                'domain' => 'http://manage.riggsdesignsolutions.com',
                                'header' => [
                                    'X-User-Email' => '{{$jason.email}}',
                                    'X-User-Token' => '{{$jason.authentication_token}}',
                                    'Authorization' => 'Bearer {{$jason.authentication_token}}',
                                    'Logged-In' => 'true',
                                    'Logged-Date' => Helpers::userTimeCurrent('Y-m-d H:i:s')
                                ],
                            ],
                            'success' => [
                                'type' => '$href',
                                'options' => [
                                    'url' => 'http://manage.riggsdesignsolutions.com/api/json/children-status',
                                    'transition' => 'replace',
                                ],
                                // 'type' => '$util.banner',
                                // 'options' => array(
                                //     'title' => 'Authentication saved to Session',
                                //     'description' => '{{session.}}',
                                // ) ,
                            ],
                        ],
                        'error' => [
                            'type' => '$util.banner',
                            'options' => [
                                'title' => 'Error',
                                'description' => 'Something went wrong. Please check if you entered your email and password correctly',
                            ],
                        ],
                    ],
                ],
                6 => [
                    'type' => 'label',
                    'style' => [
                        'size' => '16',
                        'font' => 'HelveticaNeue-Bold',
                        'text' => 'or',
                        'padding' => '10',
                        'align' => 'center',
                    ],
                ],
            ]
        ];
        $jsonReturn = AndroidApp::createJasonetteWrapper($options);
        $jsonReturn['$jason']['body']['style'] = [
            'border' => 'none',
            'background' => 'rgb(100,100,100)',
        ]; 
        $jsonReturn['$jason']['body']['header']['style'] = [
            'background' => '#646464',
            'color' => '#ffffff',
        ];

        return response()->json($jsonReturn);
        
    }

    public function jsonLogout(Request $request){
        $options = [
            "title" => "Logout",
            "description" => "",
            "bodyTitle" => "Logout",
            "includeFooter" => false,
            "sectionItems" => [
                [
                    'type' => 'space',
                    'height' => '10',
                ],
                [
                    'text' => 'Logging Out...',
                ],
            ]
        ];
        $jsonReturn = AndroidApp::createJasonetteWrapper($options);
        $jsonReturn['$jason']['body']['style'] = [
            'border' => 'none',
            'background' => 'rgb(100,100,100)',
        ]; 
        $jsonReturn['$jason']['body']['header']['style'] = [
            'background' => 'rgb(100,100,100)',
            'color' => '#ffffff',
        ];
        $jsonReturn['$jason']['head']['actions'] = [
            '$load' => [
                "type" => '$session.reset',
                "options" => [
                    "domain" => "http://manage.riggsdesignsolutions.com",
                    'header' => [
                        'X-User-Email' => '',
                        'X-User-Token' => '',
                        'Authorization' => '',
                        'Logged-In' => 'false',
                        'Logged-Date' => ''
                    ],
                ],
                "success" => [
                    "type" => '$href',
                    "options" => [
                        "url" => url('/json/login'),
                        'transition' => 'push',
                    ]
                ]
            ]
        ];

        return response()->json($jsonReturn);
        
    }

    public function handleJsonLogin(Request $request){
        $input = $request->all();
        $error = false;

        if( (isset($input['email']) &&  !empty($input['email'])) && (isset($input['password']) &&  !empty($input['password'])) ){
            
            if (\Auth::attempt(['email' => $input['email'], 'password' => $input['password']])){
                $user = User::find(\Auth::id());
            }else{
                $error = true;
            }
        }else{
            $error = true;
        }

        if($error){
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }else{
            $jsonReturn = [
                "id" => $user->id,
                "email" => $user->email,
                "authentication_token" => $user->api_token
            ];

            return response()->json($jsonReturn);
        }
    }
}
