<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\AndroidApp;
use Carbon\Carbon;

class UserController extends BaseController
{
    
    /**
     * Display the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if($this->jsonRequest){
            $user = \Auth::guard('api')->user();
            $children = $user->children;

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
                    ],
                    'action' => [
                        'type' => '$href',
                        'options' => [
                            'url' => 'http://manage.riggsdesignsolutions.com/api/json/children-status',
                            'transition' => 'replace',
                        ],
                    ]
                ];
                $childInfo[] = [
                    'type' => 'label',
                    'text' => $child->birthdate,
                    'style' => [
                        'align' => 'center',
                        'size' => '13'
                    ]
                ];

                $childComponent = [
                    'type' => 'vertical',
                    'components' => $childInfo
                ];
                
                $childrenComponents[] = $childComponent;
                $childrenOnly[] = $child->toArray();
            }


            $jsonReturn = [];

            $options = [
                "title" => "User Page",
                "description" => "All about you",
                "bodyTitle" => "User Information",
                "includeFooter" => true,
                "sectionItems" => $childrenComponents
            ];

            $jsonReturn = AndroidApp::createJasonetteWrapper($options);
            $jsonReturn['$jason']['head']['actions'] = [
                '$pull' => [
                    "type" => '$reload'
                ]
            ];

            return response()->json($jsonReturn);
        }else{
            $user = User::find(\Auth::id());
            return view('user')->with('user', $user);
        }
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
    public function show(User $user)
    {
        return view('user')->with('user', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
