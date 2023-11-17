<?php

use Illuminate\Database\Seeder;
use App\Location;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seeds = [
	        'locations' => [
		        0 => [
		        	'name'	=> 'Gulf View',
		        	'cid'	=> 1,
		        ],
		        1 => [
		        	'name'	=> 'Trincity',
		        	'cid'	=> 1,
		        ],
		        2 => [
		        	'name'	=> 'Alyce Glen',
		        	'cid'	=> 1,
		        ],
		        3 => [
		        	'name'	=> 'St Augustine',
		        	'cid'	=> 1,
		        ],
		        4 => [
		        	'name'	=> 'Broadway',
		        	'cid'	=> 1,
		        ],
		        5 => [
		        	'name'	=> 'Point Fortin',
		        	'cid'	=> 1,
		        ],
		        6 => [
		        	'name'	=> 'El Dorado',
		        	'cid'	=> 1,
		        ],
		        7 => [
		        	'name'	=> 'Westmoorings',
		        	'cid'	=> 1,
		        ],
		        8 => [
		        	'name'	=> 'Diego Martin',
		        	'cid'	=> 1,
		        ],
		        9 => [
		        	'name'	=> 'French Street',
		        	'cid'	=> 1,
		        ],
		        10 => [
		        	'name'	=> 'St Ann S',
		        	'cid'	=> 1,
		        ],
		        11 => [
		        	'name'	=> 'Maraval',
		        	'cid'	=> 1,
		        ],
		        12 => [
		        	'name'	=> 'Chaguanas',
		        	'cid'	=> 1,
		        ],
		        13 => [
		        	'name'	=> 'Ridgewood',
		        	'cid'	=> 1,
		        ],
		        14 => [
		        	'name'	=> 'Glencoe',
		        	'cid'	=> 1,
		        ],
		        15 => [
		        	'name'	=> 'Marabella Supercenter',
		        	'cid'	=> 1,
		        ],
		        16 => [
		        	'name'	=> 'Crews Inn',
		        	'cid'	=> 1,
		        ],
		        17 => [
		        	'name'	=> 'Maraval Exp',
		        	'cid'	=> 1,
		        ],
		        18 => [
		        	'name'	=> 'Diskomart Tunapuna',
		        	'cid'	=> 1,
		        ],
		        19 => [
		        	'name'	=> 'Diskomart Arouca',
		        	'cid'	=> 1,
		        ],
		        20 => [
		        	'name' 	=> 'Petit Valley',
		        	'cid' 	=> 1,
		        ],
		        21 => [
		        	'name' 	=> 'Port of Spain',
		        	'cid' 	=> 1,
		        ],
		        22 => [
		        	'name' 	=> 'San Fernando',
		        	'cid' 	=> 1,
		        ],
		        23 => [
		        	'name' 	=> 'Arima',
		        	'cid' 	=> 1,
		        ],
		        24 => [
		        	'name' 	=> 'Chaguaramas',
		        	'cid' 	=> 1,
		        ],
		        25 => [
		        	'name' 	=> 'Macoya',
		        	'cid' 	=> 1,
		        ],
		        26 => [
		        	'name' 	=> 'Tunapuna',
		        	'cid' 	=> 1,
		        ],
		        27 => [
		        	'name' 	=> 'La Romain',
		        	'cid' 	=> 1,
		        ],
		        28 => [
		            'name' 	=> 'Rodney Heights',
		            'cid' 	=> 2,
		        ],           
		        29 => [
		            'name' 	=> 'Rodney Bay',
		            'cid' 	=> 2,
		        ], 
		        30 => [
		            'name' 	=> 'Sunny Acres',
		            'cid' 	=> 2,
		        ],
		        31 => [
		            'name' 	=> 'La Clery',
		            'cid' 	=> 2,
		        ],           
		        32 => [
		            'name' 	=> 'Waterfront',
		            'cid' 	=> 2,
		        ], 
		        33 => [
		            'name' 	=> 'Boulvard',
		            'cid' 	=> 2,
		        ],
		        34 => [
		            'name' 	=> 'New Dock',
		            'cid' 	=> 2,
		        ],           
		        35 => [
		            'name' 	=> 'La Tourney',
		            'cid' 	=> 2,
		        ], 
		        36 => [
		            'name' 	=> 'Cul De Sac',
		            'cid' 	=> 2,
		        ],
		        37 => [
		        	'name' 	=> 'La Retraite',
		        	'cid' 	=> 2,
		        ],
		        38 => [
		        	'name' 	=> 'Gros Islet',
		        	'cid' 	=> 2,
		        ],
		        39 => [
		        	'name' 	=> 'Castries',
		        	'cid' 	=> 2,
		        ],
		        40 => [
		        	'name' 	=> 'Vieux Fort',
		        	'cid' 	=> 2,
		        ],
		        41 => [
		        	'name' 	=> 'Providence',
		        	'cid' 	=> 3,
		        ],
		        42 => [
		        	'name'	=> 'Worthing',
		        	'cid'	=> 4,
		        ],
		        43 => [
		        	'name'	=> 'Sunset Crest',
		        	'cid'	=> 4,
		        ],
		        44 => [
		        	'name'	=> 'Oistins',
		        	'cid'	=> 4,
		        ],
		        45 => [
		        	'name'	=> 'Sky Mall',
		        	'cid'	=> 4,
		        ],
		        46 => [
		        	'name'	=> 'Warrens',
		        	'cid'	=> 4,
		        ],
		        47 => [
		        	'name'	=> 'Coverley',
		        	'cid'	=> 4,
		        ],
		        48 => [
		        	'name'	=> 'Six Roads',
		        	'cid'	=> 4,
		        ],
		        49 => [
		        	'name'	=> 'Quayside',
		        	'cid'	=> 4,
		        ],
		        50 => [
		        	'name'	=> 'Pierhead',
		        	'cid'	=> 4,
		        ],
		        51 => [
		        	'name'	=> 'Sheraton',
		        	'cid'	=> 4,
		        ],
		        52 => [
		        	'name' 	=> 'Bridgetown',
		        	'cid' 	=> 4,
		        ],
		        53 => [
		        	'name'	=> 'Six Cross Roads',
		        	'cid' 	=> 4,
		        ],
		        54 => [
		        	'name' 	=> 'St. Michael',
		        	'cid' 	=> 4,
		        ],
		        55 => [
		        	'name' 	=> 'Holetown',
		        	'cid' 	=> 4,
		        ],
		        56 => [
		        	'name'	=> 'Kingstown',
		        	'cid'	=> 5,
		        ],
		        57 => [
		        	'name'	=> 'Arnos Vale',
		        	'cid'	=> 5,
		        ],
		        58 => [
		        	'name'	=> 'Stoney Ground',
		        	'cid'	=> 5,
		        ],
		    ],
    	];

       	foreach ($seeds['locations'] as $key => $seed) {
       		Location::updateOrCreate([
       			'name' => $seed['name'],
       			'country_id' => $seed['cid'],
       		],
       		[
       			'name' => $seed['name'],
       			'country_id' => $seed['cid'],
       		]);
       	}
    }
}
