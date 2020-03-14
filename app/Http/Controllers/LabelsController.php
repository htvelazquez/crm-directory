<?php

namespace App\Http\Controllers;

class LabelsController extends Controller
{
    public function get() {

        return response()->json([
            "countries" => [
                ["country" => [ "country_id" => 1 , "country_name" => "Amsterdam"   , "continent" => "Europe"    ]],
                ["country" => [ "country_id" => 2 , "country_name" => "London"      , "continent" => "Europe"    ]],
                ["country" => [ "country_id" => 3 , "country_name" => "Paris"       , "continent" => "Europe"    ]],
                ["country" => [ "country_id" => 4 , "country_name" => "Washington"  , "continent" => "America"   ]],
                ["country" => [ "country_id" => 5 , "country_name" => "Mexico City" , "continent" => "America"   ]],
                ["country" => [ "country_id" => 6 , "country_name" => "Buenos Aires", "continent" => "America"   ]],
                ["country" => [ "country_id" => 7 , "country_name" => "Sydney"      , "continent" => "Australia" ]],
                ["country" => [ "country_id" => 8 , "country_name" => "Wellington"  , "continent" => "Australia" ]],
                ["country" => [ "country_id" => 9 , "country_name" => "Canberra"    , "continent" => "Australia" ]],
                ["country" => [ "country_id" => 10, "country_name" => "Beijing"     , "continent" => "Asia"      ]],
                ["country" => [ "country_id" => 11, "country_name" => "New Delhi"   , "continent" => "Asia"      ]],
                ["country" => [ "country_id" => 12, "country_name" => "Kathmandu"   , "continent" => "Asia"      ]],
                ["country" => [ "country_id" => 13, "country_name" => "Cairo"       , "continent" => "Africa"    ]],
                ["country" => [ "country_id" => 14, "country_name" => "Cape Town"   , "continent" => "Africa"    ]],
                ["country" => [ "country_id" => 15, "country_name" => "Kinshasa"    , "continent" => "Africa"    ]]
            ]
        ], 200);
    }
}
