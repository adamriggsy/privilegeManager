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

        if(isset($options['includeFooter']) && $options['includeFooter']){
        	$jasonetteStructure['$jason']['body']['footer']['tabs'] = 
        	[
			    "style" => [
			        "background" => "rgba(255,255,255,0.9)",
			        "color" => "#009efa"
			    ],
			    "items" => [
			    	[
				    	'image' => url('/images/logout.png'),
				    	'text' => 'Logout',
				    	'style' => [
				    	],
				    	'url' => 'http://manage.riggsdesignsolutions.com/api/json/logout'
				    ],
				    [
				    	'image' => url('/images/user.png'),
				    	'text' => 'User',
				    	'style' => [
				    	],
				    	'url' => 'http://manage.riggsdesignsolutions.com/api/json/user'
				    ],
				    [
				    	'image' => url('/images/children.png'),
				    	'text' => 'Children',
				    	'style' => [
				    	],
				    	'url' => 'http://manage.riggsdesignsolutions.com/api/json/children-status'
				    ]
			    ]
			];
        }

        return $jasonetteStructure;
    }
}
