<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AndroidApp extends Controller
{
    /*
    *	Creates the main skeleton of the Jasonette JSON structure
    *	@param $options Array
    *   return Array
    */

    public static function createJasonetteWrapper($options = []){
    	$jasonetteStructure = [
            '$jason' => [
                'head' => [
                    'title' => isset($options['title']) ? $options['title'] : 'Manage Privileges',
                    'description' => isset($options['description']) ? $options['description'] : "Manage your children's privileges",
                    'icon' => '',
                    'offline' => 'true',
                ],
                'body' => [
                    'header' => [
                        "title" => isset($options['bodyTitle']) ? $options['bodyTitle'] : 'New Screen',
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
                            'items' => isset($options['sectionItems']) ? $options['sectionItems'] : []
                        ]
                    ]
                ]
            ]
        ];

        if(isset($options['drawer']) && $options['drawer']){
        	$jasonetteStructure['drawer'] = [
			    "activator" => [
			       "image": url('/images/menu-512.png'),
			        "style" => [
			            "height": "21",
			            "top": "50",
			            "left": "50",
			       ]
			    ],
			    "contents" => [
					"style" => [
						"background": "rgba(255,255,255,0.8)",
						"color:disabled": "#cecece",
						"color": "#009efa"
					],
			    	"items"=> [
			      		[
					        "image": "",
					        "text": "Me",
					        "style" => [
					          "height": "21"
					        ],
					        "url": "https://raw.githubusercontent.com/Jasonette/Twitter-UI-example/master/me.jsonhttp://manage.riggsdesignsolutions.com/api/json/children-status"
					    ],
					    [
					        "image": "",
					        "text": "Logout",
					        "style" => [
					          "height": "21"
					        ],
					        "url": "http://manage.riggsdesignsolutions.com/api/json/logout"
			      		],
			  		]
			    ]
			]
        ];

        return $jasonetteStructure;
    }
}
