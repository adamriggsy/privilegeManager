<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ChildPrivilegeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('child_privilege')->insert([
            [
            	'child_id' => 1,
            	'privilege_id' => 1,
	            'start_ban' => Carbon::now()->subDays('20')->format('Y-m-d'),
	    		'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
	    		'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
	    	],
            [
                'child_id' => 1,
                'privilege_id' => 1,
                'start_ban' => Carbon::now()->subDays('19')->format('Y-m-d'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'child_id' => 1,
                'privilege_id' => 1,
                'start_ban' => Carbon::now()->subDays('18')->format('Y-m-d'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'child_id' => 1,
                'privilege_id' => 1,
                'start_ban' => Carbon::now()->subDays('1')->format('Y-m-d'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'child_id' => 1,
                'privilege_id' => 1,
                'start_ban' => Carbon::now()->subDays('2')->format('Y-m-d'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'child_id' => 1,
                'privilege_id' => 1,
                'start_ban' => Carbon::now()->subDays('3')->format('Y-m-d'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'child_id' => 1,
                'privilege_id' => 1,
                'start_ban' => Carbon::now()->subDays('4')->format('Y-m-d'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'child_id' => 1,
                'privilege_id' => 1,
                'start_ban' => Carbon::now()->subDays('5')->format('Y-m-d'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'child_id' => 1,
                'privilege_id' => 1,
                'start_ban' => Carbon::now()->subDays('6')->format('Y-m-d'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'child_id' => 1,
                'privilege_id' => 2,
                'start_ban' => Carbon::now()->subDays('20')->format('Y-m-d'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'child_id' => 1,
                'privilege_id' => 2,
                'start_ban' => Carbon::now()->subDays('19')->format('Y-m-d'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'child_id' => 1,
                'privilege_id' => 2,
                'start_ban' => Carbon::now()->subDays('18')->format('Y-m-d'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'child_id' => 1,
                'privilege_id' => 3,
                'start_ban' => Carbon::now()->subDays('15')->format('Y-m-d'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'child_id' => 1,
                'privilege_id' => 3,
                'start_ban' => Carbon::now()->subDays('14')->format('Y-m-d'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'child_id' => 1,
                'privilege_id' => 3,
                'start_ban' => Carbon::now()->subDays('13')->format('Y-m-d'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'child_id' => 1,
                'privilege_id' => 3,
                'start_ban' => Carbon::now()->subDays('12')->format('Y-m-d'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'child_id' => 1,
                'privilege_id' => 3,
                'start_ban' => Carbon::now()->subDays('11')->format('Y-m-d'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'child_id' => 1,
                'privilege_id' => 3,
                'start_ban' => Carbon::now()->subDays('10')->format('Y-m-d'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'child_id' => 2,
                'privilege_id' => 2,
                'start_ban' => Carbon::now()->subDays('1')->format('Y-m-d'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'child_id' => 2,
                'privilege_id' => 2,
                'start_ban' => Carbon::now()->subDays('2')->format('Y-m-d'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'child_id' => 2,
                'privilege_id' => 2,
                'start_ban' => Carbon::now()->subDays('3')->format('Y-m-d'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'child_id' => 2,
                'privilege_id' => 2,
                'start_ban' => Carbon::now()->subDays('4')->format('Y-m-d'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'child_id' => 2,
                'privilege_id' => 2,
                'start_ban' => Carbon::now()->subDays('5')->format('Y-m-d'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'child_id' => 2,
                'privilege_id' => 2,
                'start_ban' => Carbon::now()->subDays('6')->format('Y-m-d'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
        ]);
    }
}
