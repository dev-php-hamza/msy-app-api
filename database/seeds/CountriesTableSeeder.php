<?php

use Illuminate\Database\Seeder;
use App\Country;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = [
            0 => [
                'name' => 'Trinidad and Tobago',
                'country_code' => 'TT',
                'card_territory_code' => '42',
                'calling_code' => '+1',
            ],
            1 => [
                'name' => 'Saint Lucia',
                'country_code' => 'LC',
                'card_territory_code' => '62',
                'calling_code' => '+1',
            ],
            2 => [
                'name' => 'Guyana',
                'country_code' => 'GY',
                'card_territory_code' => '32',
                'calling_code' => '+1',
            ],
            3 => [
                'name' => 'Barbados',
                'country_code' => 'BB',
                'card_territory_code' => '52',
                'calling_code' => '+1',
            ],
            4 => [
                'name' => 'Saint Vincent and the Grenadines',
                'country_code' => 'VC',
                'card_territory_code' => '72',
                'calling_code' => '+1',
            ],
        ];
        foreach ($countries as $country){
            Country::updateOrCreate([
                'name' => $country['name']
        	],
        	[
                'name' => $country['name'],
                'country_code' => $country['country_code'],
                'calling_code' => $country['calling_code'],
                'card_territory_code' => $country['card_territory_code']
            ]);
        }
    }
}
