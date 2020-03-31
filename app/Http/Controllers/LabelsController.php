<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Label;

class LabelsController extends Controller
{
    public function get(Request $request) {
        $accountId = 1; // get as parameter

        $dbLabels = Label::where('account_id',$accountId)->get();

        if (empty($dbLabels)) return response()->json(['suggestions' => []], 200);

        $labels = [];
        foreach ($dbLabels as $dbLabel) {
            $labels[] = $dbLabel->name;
        }

        if ($request->has('term')){
            $labels = preg_grep("/.*{$request->input('term')}.*/i",$labels);
        }

        return response()->json(['suggestions' => array_values($labels)], 200);
    }
}
