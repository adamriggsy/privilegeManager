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
			        "background" => "rgba(255,255,255,0.8)",
                    "color" => "#000000"
			    ],
			    "items" => [
			    	[
				    	'image' => url('/images/children.png'),
				    	'style' => [
				    	    "height" => "60",
				    	    "width" => "60"
				    	],
				    	'url' => 'http://manage.riggsdesignsolutions.com/api/json/children-status'
				    ],
				    [
				    	'image' => url('/images/user.png'),
				    	'style' => [
				    	    "height" => "60",
				    	    "width" => "60"
				    	],
				    	'url' => 'http://manage.riggsdesignsolutions.com/api/json/user'
				    ],
				    [
				    	'image' => url('/images/logout.png'),
				    	'style' => [
				    	    "height" => "60",
				    	    "width" => "60"
				    	],
				    	'url' => 'http://manage.riggsdesignsolutions.com/api/json/logout'
				    ],
			    ]
			];
        }

        return $jasonetteStructure;
    }
}
