<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PrivilegeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('privileges')->insert([
            [
            	'name' => 'Screen Time',
	            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
	    		'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
	    	],
	    	[
            	'name' => 'Treats',
	            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
	    		'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
	    	],
	    	[
            	'name' => 'Play Dates',
	            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
	    		'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
	    	]
        ]);
    }
}
