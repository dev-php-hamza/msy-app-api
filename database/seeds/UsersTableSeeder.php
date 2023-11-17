<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Role;
use App\User;
use App\UserInfo;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$seed = [
    		0 => [
    			'first_name'  => 'John',
    			'last_name' => 'Doe',
    			'email' => 'testadmin@example.com',
    			'password' => Hash::make('admin123'),
    			'role_id' => 1,
    		],
    		1 => [
    			'first_name'  => 'Mark',
    			'last_name' => 'Vain',
    			'email' => 'testuser@example.com',
    			'password' => Hash::make('user123'),
    			'role_id' => 2,
    		]
    	];

    	foreach ($seed as $key => $user) {
    	    $user_id = User::updateOrCreate([
    	    	'first_name' => $user['first_name'],
    	    	'last_name' => $user['last_name'],
    	    ],
    	    [
    	    	'first_name'  => $user['first_name'],
    	    	'last_name' => $user['last_name'],
    	    	'email' => $user['email'],
    	    	'password' => $user['password'],
    	    	'role_id' => $user['role_id'],
    	    ])->id;

            UserInfo::create([
                'user_id' => $user_id,
                'country' => 'TT',
                'gender'  => 'Male'
            ]);
    	}        
    }
}
