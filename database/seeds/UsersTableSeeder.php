<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        DB::table('users')->insert([
            [
                'name' => 'Adam Riggs',
                'email' => 'adam.riggsy@gmail.com',
                'password' => bcrypt('Sophie2006*'),
                'api_token' => Str::random(60),
                'timezone' => 'US/Mountain',
                'my_privileges' => '[1,2]',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Test User',
                'email' => 'testUser@gmail.com',
                'password' => bcrypt('Sophie2006*'),
                'api_token' => Str::random(60),
                'timezone' => 'US/Mountain',
                'my_privileges' => '[1,2,3]',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]
        ]);
    }
}
