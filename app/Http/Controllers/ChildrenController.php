<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Child;
use App\User;
use App\Privilege;
use Carbon\Carbon;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\AndroidApp;

class ChildrenController extends BaseController
{
    public $child = null;
    public $childAvailPrivileges = null;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    
        if(! \App::runningInConsole()){
            if(!is_null(\Route::current()->parameter('id'))){
                $id = (int) \Route::current()->parameter('id');
            }else if(!is_null(\Route::current()->parameter('child'))){
                $id = (int) \Route::current()->parameter('child');
            }else{
                $id = false;
            }

            if($id){
                $this->child = Child::find($id);
                //get the available privileges for the child
                $this->childAvailPrivileges = Privilege::whereIn('id', $this->child->privileges)->get();
                $this->userAvailPrivileges = Privilege::whereIn('id', $this->child->user->my_privileges)->get();
            }

        }else{
            $id = 1;
        }
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
        $user = User::find(\Auth::id());
        $child = new Child;

        $child->name = $request->get('childName');
        $child->birthdate = $request->get('birthdate');
        $child->privileges = $user->my_privileges;

        if($user->children()->save($child)){
            $request->session()->flash('status', 'You have successfully added a new child');
        }else{
            $request->session()->flash('error', 'Could not create the child');
        }

        
        return redirect()->route('child.create');
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

    public function managePrivileges($id, $startDay = false, $endDay = false, Request $request){
        if(self::isUserChild($id, $this->jsonRequest) !== false){
            if($this->jsonRequest){
                $startDay = Carbon::now()->format('Y-m-d');
                $endDay = Carbon::now()->addDays(7)->format('Y-m-d');
            }

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
            
            if($this->jsonRequest){
                $childStatuses = [
                    [
                        'type' => 'label',
                        'text' => "Last updated: " . Helpers::userTimeCurrent('m-d-Y H:i:s'),
                        'style' => [
                            'align' => 'center'
                        ]
                    ]
                ];

                foreach($child->privilegeStatus as $date => $privileges){
                    $dayInfo = [];
                    $dayInfo[] = [
                        'type' => 'label',
                        'text' => $date,
                        'style' => [
                            'padding' => 20,
                            'align' => 'center',
                            'size' => '18'
                        ]
                    ];

                    foreach($privileges as $name => $status){
                        $statusText = $name . ' - ';
                        $statusText .= $status ? 'no' : 'yes';
                        $statusColor = $status ? '#f5c6cb' : '#c3e6cb';
                        $modalLink = $status ? 'http://manage.riggsdesignsolutions.com/api/json/child/' . $child->id . '/privilege/restore' : 'http://manage.riggsdesignsolutions.com/api/json/child/' . $child->id . '/privilege/ban';

                        $modalLink .= "?date=" . $date;
                        $modalLink .= "&privilege=" . urlencode($name);

                        $dayInfo[] = [
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

                    $dayComponent = [
                        'type' => 'vertical',
                        'components' => $dayInfo
                    ];

                    $childStatuses[] = $dayComponent;
                }

                

                $options = [
                    "title" => "Manage Child",
                    "description" => "Manage a child's privileges",
                    "bodyTitle" => $this->child->name . "'s privileges",
                    "includeFooter" => true,
                    "sectionItems" => $childStatuses
                ];

                $jsonReturn = AndroidApp::createJasonetteWrapper($options);
                $jsonReturn['$jason']['head']['actions'] = [
                '$pull' => [
                    "type" => '$reload'
                ]
            ];

                return response()->json($jsonReturn);
            }else{
                return view('child-manage')->withChild($child)->with('allPrivileges', $allPrivileges)->with('jsonRequest', $this->jsonRequest);
            }
        }else{
            $request->session()->flash('error', "You don't have the ability to manage that child");
            return redirect()->route('user.index');
        }
    }

    public function banPrivilege($childID, Request $request){
        if(self::isUserChild($childID, $this->jsonRequest) !== false){
            
            if($this->jsonRequest){
                $user = \Auth::guard('api')->user();

                $renderedForm = view('includes.forms.child-privilege-ban')
                    ->with('child', $this->child)
                    ->with('parameters', $request->all())
                    ->with('allPrivileges', $this->childAvailPrivileges)
                    ->with('action', 'ban')
                    ->with('jsonRequest', true)
                    ->with('api_token', $user->api_token)
                    ->render();

                $options = [
                    "title" => "Ban Privilege",
                    "description" => "Ban a child's privilege",
                    "bodyTitle" => "Ban - " . $this->child->name,
                    "includeFooter" => true,
                    "sectionItems" => [
                        // [
                        //     'type' => 'space',
                        //     'height' => '10',
                        // ],
                        // [
                        //     'type' => 'html',
                        //     'text' => $renderedForm,
                        //     'style' => [
                        //         'height' => '100%',
                        //     ],
                        //     'action' => [
                        //         'type' => '$default'
                        //     ],
                        // ],
                    ]
                ];

                $jsonReturn = AndroidApp::createJasonetteWrapper($options);
                $jsonReturn['$jason']['head']['data']['children'] = [];
                $jsonReturn['$jason']['head']['actions'] = [
                    'handleUrl' => [
                        'type' => '$util.banner',
                        "options" => [
                            'title' => 'Link Clicked',
                            "description" => '{{$jason.url}}'
                        ]
                    ]
                ];
                $jsonReturn['$jason']['body']['background'] = [
                    "type" => "html",
                    "text" => $renderedForm,
                    "action" => [
                        "trigger" => "handleUrl"
                    ],
                ];

                return response()->json($jsonReturn);
            }else{
                return view('child-privilege-management')
                    ->with('child', $this->child)
                    ->with('parameters', $request->all())
                    ->with('allPrivileges', $this->childAvailPrivileges)
                    ->with('action', 'ban')
                    ->with('jsonRequest', false)
                    ->with('api_token', false);
            }
        }
    }

    public function banPrivilegeProcess($childID, Request $request){
        if(self::isUserChild($childID, $this->jsonRequest) !== false){
            
            
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

            if($this->jsonRequest){
                // $options = [
                //     "title" => "Ban Privilege Submitted",
                //     "description" => "Ban submitted",
                //     "bodyTitle" => "Done - ",
                //     "includeFooter" => true,
                //     "sectionItems" => [
                //         [
                //             'type' => 'space',
                //             'height' => '10',
                //         ],
                //         [
                //             'type' => 'textfield',
                //             'name' => 'email',
                //             'style' => [
                //                 'size' => '20',
                //                 'color' => '#8bb92d',
                //                 'font' => 'HelveticaNeue-Bold',
                //                 'background' => '#575757',
                //                 'padding' => '20',
                //             ],
                //             'placeholder' => 'enter email',
                //         ],
                //     ]
                // ];

                // $jsonReturn = AndroidApp::createJasonetteWrapper($options);
                // $jsonReturn['$jason']['head']['actions'] = [
                //     '$load' => [
                //         "type" => 'href',
                //         "options" => [
                //             "url" => "http://manage.riggsdesignsolutions.com/api/json/children-status",
                //             "transition" => "modal"
                //         ]
                //     ]
                // ];
                // return response()->json($jsonReturn);
                return view('includes.child.jsonFormSubmission');
            }else{
                if($numAdded > 0){
                    $request->session()->flash('status', 'You have successfully added new privilege bans');
                }else{
                    $request->session()->flash('error', 'No new privilege bans were added.');
                }

                return redirect()->route('child.manage', ['id' => $this->child->id]);
            }
        }
    }

    public function restorePrivilege($childID, Request $request){
        if(self::isUserChild($childID, $this->jsonRequest) !== false){
            if($this->jsonRequest){
                $user = \Auth::guard('api')->user();

                $renderedForm = view('includes.forms.child-privilege-restore')
                    ->with('child', $this->child)
                    ->with('parameters', $request->all())
                    ->with('allPrivileges', $this->childAvailPrivileges)
                    ->with('action', 'ban')
                    ->with('jsonRequest', true)
                    ->with('api_token', $user->api_token)
                    ->render();

                $options = [
                    "title" => "Restore Privilege",
                    "description" => "Restore a child's privilege",
                    "bodyTitle" => "Restore - " . $this->child->name,
                    "includeFooter" => true,
                    "sectionItems" => [
                        // [
                        //     'type' => 'space',
                        //     'height' => '10',
                        // ],
                        // [
                        //     'type' => 'html',
                        //     'text' => $renderedForm,
                        //     'style' => [
                        //         'height' => '100%',
                        //     ],
                        //     'action' => [
                        //         'type' => '$default'
                        //     ],
                        // ],
                    ]
                ];

                $jsonReturn = AndroidApp::createJasonetteWrapper($options);
                $jsonReturn['$jason']['head']['actions'] = [
                    'handleUrl' => [
                        'type' => '$util.banner',
                        "options" => [
                            'title' => 'Link Clicked',
                            "description" => '{{$jason.url}}'
                        ]
                    ]
                ];
                $jsonReturn['$jason']['body']['background'] = [
                    "type" => "html",
                    "text" => $renderedForm,
                    "action" => [
                        "trigger" => "handleUrl"
                    ],
                ];

                return response()->json($jsonReturn);
            }else{
                return view('child-privilege-management')
                    ->with('child', $this->child)
                    ->with('parameters', $request->all())
                    ->with('allPrivileges', $this->childAvailPrivileges)
                    ->with('action', 'restore')
                    ->with('jsonRequest', false)
                    ->with('api_token', $user->api_token);
            }
        }
    }

    public function restorePrivilegeProcess($childID, Request $request){
        if(self::isUserChild($childID, $this->jsonRequest) !== false){
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

            if($this->jsonRequest){
                // $options = [
                //     "title" => "Restore Privilege Submitted",
                //     "description" => "Restoration submitted",
                //     "bodyTitle" => "Done - ",
                //     "includeFooter" => true,
                //     "sectionItems" => [
                //         [
                //             'type' => 'space',
                //             'height' => '10',
                //         ],
                //         [
                //             'type' => 'textfield',
                //             'name' => 'email',
                //             'style' => [
                //                 'size' => '20',
                //                 'color' => '#8bb92d',
                //                 'font' => 'HelveticaNeue-Bold',
                //                 'background' => '#575757',
                //                 'padding' => '20',
                //             ],
                //             'placeholder' => 'enter email',
                //         ],
                //     ]
                // ];

                // $jsonReturn = AndroidApp::createJasonetteWrapper($options);
                // $jsonReturn['$jason']['head']['actions'] = [
                //     '$load' => [
                //         "type" => 'href',
                //         "options" => [
                //             "url" => "http://manage.riggsdesignsolutions.com/api/json/children-status",
                //             "transition" => "modal"
                //         ]
                //     ]
                // ];
                // return response()->json($jsonReturn);
                return view('includes.child.jsonFormSubmission');
            }else{
                if($numProcessed > 0){
                    $request->session()->flash('status', 'You have successfully restored the privileges');
                }else{
                    $request->session()->flash('error', 'Not able to restore privileges');
                }
                return redirect()->route('child.manage', ['id' => $this->child->id]);
            }
        }
    }

    public function childPrivilegesUpdate(\Request $request){
        $privileges = $request::input('privileges');

        $this->child->privileges = $privileges;
        $wasSaved = $this->child->save();

        return response()->json($wasSaved);
    }

    public static function isUserChild($childID, $isJsonRequest){
        
        if($isJsonRequest){
            return array_search($childID, array_column(\Auth::guard('api')->user()->children->toArray(), 'id'));
        }else{
            return array_search($childID, array_column(User::find(\Auth::id())->children->toArray(), 'id'));
        }
    }

    public static function checkPrivilegeBan(Child $child, $privilegeID, $date){
        return $child->privileges()
                    ->where('privilege_id', $privilegeID)
                    ->where('start_ban', '=', $date)
                    ->exists();
    }
}
