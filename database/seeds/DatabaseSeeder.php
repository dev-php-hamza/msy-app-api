<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        /*Roles seeder first to create user*/
        $this->call(RolesTableSeeder::class);

        /*Users comes after Roles Seeder*/
        $this->call(UsersTableSeeder::class);

        /**/
        $this->call(CountriesTableSeeder::class);

        /*Locations comes after Countries seeder here.*/
        $this->call(LocationsTableSeeder::class);

        /*Product Image LookUp comes after Countries seeder here.*/
        $this->call(ProductImageLookUpTableSeeder::class);

    }
}
