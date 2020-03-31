<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LabelsController extends Controller
{
    public function get(Request $request) {

        $labels = [
            "Recruiting","PHP Devs","Buenos Aires","Washington","Mexico City","Buenos Aires","Sydney","Wellington","Canberra","Beijing","New Delhi","Kathmandu","Cairo","Cape Town","Kinshasa"
        ];

        if ($request->has('term')){
            $labels = preg_grep("/.*{$request->input('term')}.*/i",$labels);
        }

        return response()->json(['suggestions' => array_values($labels)], 200);
    }
}
