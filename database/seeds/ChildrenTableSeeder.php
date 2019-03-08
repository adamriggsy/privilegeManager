<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ChildrenTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('children')->insert([
            [
            	'name' => 'Sophie Riggs',
	            'birthdate' => Carbon::parse('December 31, 2006'),
	            'user_id' => 1,
	            'privileges' => '[1,2,3]',
	            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
	    		'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
	    	],
	    	[
            	'name' => 'Landon Riggs',
	            'birthdate' => Carbon::parse('September 2, 2009'),
	            'user_id' => 1,
	            'privileges' => '[1,2,3]',
	            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
	    		'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
	    	],
	    	[
            	'name' => 'Kambria Riggs',
	            'birthdate' => Carbon::parse('July 12, 2012'),
	            'user_id' => 1,
	            'privileges' => '[1,2]',
	            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
	    		'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
	    	],
	    	[
            	'name' => 'Test Child',
	            'birthdate' => Carbon::parse('April 12, 2003'),
	            'user_id' => 2,
	            'privileges' => '[]',
	            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
	    		'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
	    	]
        ]);
    }
}
