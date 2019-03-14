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

        return $jasonetteStructure;
    }
}
