<?php

use Illuminate\Database\Seeder;
use App\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$seed = array('admin','user');
    	foreach ($seed as $key => $role) {
    		Role::updateOrCreate([
    			'name' => $role,
    		],
    		[
    			'name' => $role,
    		]);
    	}
    }
}
