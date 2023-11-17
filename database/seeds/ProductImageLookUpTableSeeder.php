<?php

use Illuminate\Database\Seeder;
use App\Country;
use App\ProductImageLookUp;

class ProductImageLookUpTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = Country::whereSwitch(1)->get();
        foreach ($countries as $key => $country) {
        	ProductImageLookUp::updateOrCreate([
                'country_id' => $country->id
        	],
        	[
                'country_id' => $country->id
            ]);
        }
    }
}
